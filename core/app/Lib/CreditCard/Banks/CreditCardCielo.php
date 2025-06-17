<?php

namespace App\Lib\CreditCard\Banks;

use App\Lib\CreditCard\Interfaces\ICreditCard;

use Cielo\API30\Merchant;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;

class CreditCardCielo implements ICreditCard{

    private $publicKey = "";
    private $privateKey = "";
    private $gateway = null;
    
    public function __construct($gateway) {
        if ($gateway == null) {
            throw new \Exception("É necessário informar o gateway válido.");
        }
        $this->gateway = $gateway;
    }


    private function getEnviroment() {
        if ($this->gateway->sandbox > 0) {
            return Environment::sandbox();
        } else {
            return Environment::production();
        }
    }

    public function createTransaction($transactionTotal, $refId, $cardData) {
        
        if (! ($transactionTotal > 0) ) {
            throw new \Exception("O valor precisa ser maior que zero.");
        }

        $this->validateCreditCardInfo($cardData);
      
        $environment = $this->getEnviroment();
        $merchant = new Merchant($this->gateway->val1, $this->gateway->val2);
        
        $sale = new Sale($refId);

        // Crie uma instância de Customer informando o nome do cliente
        $customer = $sale->customer($cardData["holder"]);
        
        // Crie uma instância de Payment informando o valor do pagamento
        $payment = $sale->payment(number_format($transactionTotal, 2, "", ""), $cardData["installments"]);

        // Crie uma instância de Credit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração.
        // Utilize setSaveCard(true) para obter o token do cartão
        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
                ->creditCard($cardData["cvv"], $cardData["brand"])
                ->setExpirationDate("{$cardData["month"]}/{$cardData["year"]}")
                ->setCardNumber($cardData["number"])
                ->setHolder($cardData["holder"])
                ->setSaveCard(true);
        
        // Crie o pagamento na Cielo
        try {
            
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

            // O token gerado pode ser armazenado em banco de dados para vendar futuras
            //$token = $sale->getPayment()->getCreditCard()->getCardToken();
            $statusTransaction = $sale->getPayment()->getStatus();

            if ($statusTransaction != 1 && $statusTransaction != 2) {
                $returnCode = $sale->getPayment()->getReturnCode();
                $returnMessage = $sale->getPayment()->getReturnMessage();
                throw new \Exception("{$returnCode} - {$returnMessage}", 400);
            }
            

            $paymentId = $sale->getPayment()->getPaymentId();
            
            // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $paymentCaptured = (new CieloEcommerce($merchant, $environment))->captureSale($paymentId, number_format($transactionTotal, 2, "", ""), 0);
            
            $paymentCaptured->setPaymentId($paymentId);
            $paymentCaptured->setCreditCard($payment->getCreditCard());
            $paymentCaptured->setInstallments($cardData["installments"]);
            $paymentCaptured->setCapturedAmount(number_format($transactionTotal, 2, "", ""));
            
            $status = $this->getTransactionStatus($paymentCaptured->getStatus());

            return (Object) Array(
                "paymentMethod" => $paymentCaptured->getType(),
                "status" => $this->toTransactionStatus($paymentCaptured->getStatus()),
                "statusEnum" => $status["status"],
                "statusDescription" => $status["message"],
                "totalReceived" => number_format($paymentCaptured->getCapturedAmount() / 100, 2, ".", ""),
                "paymentId" => $paymentCaptured->getPaymentId(),
                "brand" => $paymentCaptured->getCreditCard()->getBrand(),
                "json" => json_encode($paymentCaptured),
                "installments" => $paymentCaptured->getInstallments(),
                "authorizationCode" => $paymentCaptured->getAuthorizationCode(),
                "transactionId" => $paymentCaptured->getTid(),
                "proofOfSale" => $paymentCaptured->getProofOfSale(),
                "gateway" => "cielo"
            );

        } catch (CieloRequestException $e) {
            
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();
            
            throw new \Exception("{$error->getCode()} - {$error->getMessage()}");
        } catch (\Exception $ex) {
            
            //exit($ex->getTraceAsString());
            throw new \Exception("{$ex->getCode()} - {$ex->getMessage()}");
        }
    }


    private function validateCreditCardInfo($cardData) {

        if (empty($cardData["holder"])) {
            throw new \Exception("Holder is mandatory.");
        }
        if (empty($cardData["number"])) {
            throw new \Exception("Card number is mandatory.");
        }
        
        if (empty($cardData["cvv"])) {
            throw new \Exception("Card Security code is mandatory.");
        }
        
        if (empty($cardData["month"])) {
            throw new \Exception("Month is mandatory.");
        }
        
        if (empty($cardData["year"])) {
            throw new \Exception("Year is mandatory.");
        }
        
        if (empty($cardData["brand"])) {
            throw new \Exception("Card Brand is mandatory.");
        }
        
        if (empty($cardData["installments"]) || !($cardData["installments"] > 0)) {
            throw new \Exception("Invalid installments.");
        }
        
    }




    public function getTransactionStatus($statusCode) {
        switch ($statusCode) {
            case 0: return Array("status" => "NOT_FINISHED", "message" => "Aguardando atualização de status.");
            case 1: return Array("status" => "AUTHORIZED", "message" => "Pagamento apto a ser capturado ou definido como pago.");
            case 2: return Array("status" => "PAYMENT_CONFIRMED", "message" => "Pagamento confirmado e finalizado.");
            case 3: return Array("status" => "DENIED", "message" => "Pagamento negado por Autorizador.");
            case 10: return Array("status" => "VOIDED", "message" => "Pagamento cancelado.");
            case 11: return Array("status" => "REFUNDED", "message" => "Pagamento cancelado após 23h59 do dia de autorização.");
            case 12: return Array("status" => "PENDING", "message" => "Aguardandoretorno da instituição financeira.");
            case 13: return Array("status" => "ABORTED", "message" => "Pagamento cancelado por falha no processamento ou por ação do Antifraude.");
            case 20: return Array("status" => "SCHEDULED", "message" => "Recorrência agendada.");
            default: return Array("status" => "UNKNOW", "message" => "Desconhecido");
        }
    }
    

    public function toTransactionStatus($statusCode) {
        switch ($statusCode) {
            case 0: return 0;
            case 1: return 0;
            case 2: return 1;
            case 3: return 0;
            case 10: return 2;
            case 11: return 2;
            case 12: return 0;
            case 13: return 2;
            case 20: return 0;
            default: return 0;
        }
    }

}