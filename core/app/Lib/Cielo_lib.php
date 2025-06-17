<?php

namespace App\Lib;

use Cielo\API30\Merchant;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;
use App\Models\Invoice;
use App\Models\Transactions;
use App\Models\Deposits;

use Cielo\API30\Ecommerce\Request\CieloRequestException;

class Cielo_lib {
    
    private $publicKey = "";
    private $privateKey = "";
    
    private $gatewayId = 507;
    private $gateway = null;
    
    public function __construct() {
        $this->gateway = \App\Models\Gateway::findOrFail($this->gatewayId);
    }
    
    private function getEnviroment() {
        if ($this->gateway->sandbox > 0) {
            return Environment::sandbox();
        } else {
            return Environment::production();
        }
    }


    public function createInvoiceCreditCardTransaction($invoice, $cardData) {
        try {

            if ($invoice == null) {
                throw new \Exception("Invalid invoice.");
            }

            $transactionTotal = $invoice->total;

            $paymentCaptured = $this->createCreditcardTransaction($transactionTotal, $invoice->ref_id, $cardData);

            $cieloPaymentController = new \App\Http\Controllers\CieloPaymentController();
            $transaction = $cieloPaymentController->creditCardPayment($invoice->ref_id, $paymentCaptured);
            
            $invoice = Invoice::where("ref_id", $invoice->ref_id)->first();
            
            return Array("invoice" => $invoice, "transaction" => $transaction);

        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }


    public function createDepositCreditCardTransaction($deposit, $cardData) {
        try {

            if ($deposit == null) {
                throw new \Exception("Invalid deposit.");
            }

            $transactionTotal = $deposit->amount;

            $paymentCaptured = $this->createCreditcardTransaction($transactionTotal, $deposit->trx, $cardData);

            $cieloPaymentController = new \App\Http\Controllers\CieloPaymentController();
            $deposit = $cieloPaymentController->depositPayment($deposit->trx, $paymentCaptured);
            
            return $deposit;

        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
    
    /**
     * 
     * @param Invoice $invoice
     * @param Array $cardData
     * @return Sale
     * @throws \Exception
     */
    private function createCreditcardTransaction($transactionTotal, $refId, $cardData) {
        
        
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
            
            $paymentId = $sale->getPayment()->getPaymentId();
            
            // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $paymentCaptured = (new CieloEcommerce($merchant, $environment))->captureSale($paymentId, number_format($transactionTotal, 2, "", ""), 0);
            $paymentCaptured->setPaymentId($paymentId);
            $paymentCaptured->setCreditCard($payment->getCreditCard());
            $paymentCaptured->setInstallments($cardData["installments"]);
            $paymentCaptured->setCapturedAmount(number_format($transactionTotal, 2, "", ""));
            
            return $paymentCaptured;
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();
            
            throw new \Exception("{$error->getCode()} - {$error->getMessage()}");
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
    
    /**
     * 
     * @param String $paymentId
     * @return Sale
     */
    public function consultByPaymentId($paymentId) {
        $environment = $this->getEnviroment();
        
        $merchant = new Merchant($this->gateway->val1, $this->gateway->val2);
        
        $sale = (new CieloEcommerce($merchant, $environment))->getSale($paymentId);
        
        return $sale;
    }
    
    public function createInvoiceBoletoTransaction($invoice, $customer, $instructions =null) {

        $user = \App\Models\User::where("id", $invoice->user_id)->first();
        $dueDate = new \DateTime($invoice->due_date);
        $refId = $invoice->ref_id;
        $entityId = $invoice->id;
        $transactionTotal = $invoice->total;

        $sale = $this->createBoletoTransaction($user, $transactionTotal, $entityId, $refId, $dueDate, $customer, $instructions =null);

        $paymentId = $sale->getPayment()->getPaymentId();
        $boletoURL = $sale->getPayment()->getUrl();
        $barcode = $sale->getPayment()->getBarCodeNumber();
        $digitableLine = $sale->getPayment()->getDigitableLine();
        
        $invoice = Invoice::where("ref_id", $invoice->ref_id)->first();
        
        $invoice->boleto_transaction_id = $paymentId;
        $invoice->boleto_url = $boletoURL;
        $invoice->boleto_barcode = $barcode;
        $invoice->boleto_digitable_line = $digitableLine;
        $invoice->save();
        
        return $invoice;
    }



    public function createDepositBoletoTransaction($deposit, $instructions =null) {

        $user = \App\Models\User::where("id", $deposit->user_id)->first();
        $dueDate = new \DateTime($deposit->due_date);
        $refId = $deposit->trx;
        $entityId = $deposit->id;
        $transactionTotal = $deposit->amount;

        $compliance = \App\Models\Compliance::where("user_id", $user->id)->first();
        
        $document = "";
        if (!empty($compliance->tax_id)) {
            $document = str_replace(Array(".", "/", "-"), "", $compliance->tax_id);
        } else if (!empty($compliance->document)) {
            $document = str_replace(Array(".", "/", "-"), "", $compliance->document);
        } else if (!empty($compliance->reg_no)) {
            $document = str_replace(Array(".", "/", "-"), "", $compliance->reg_no);
        }


        if (empty($document) || empty($compliance->address_zipcode) || empty($compliance->address_state) || empty($compliance->address_city) || 
            empty($compliance->neighborhood) || empty($compliance->address) || empty($compliance->address_number) || empty($compliance->phone)) {
            throw new \Exception("É necessário cadastrar as informações de compliance.");
        }

        $customer = (object) Array(
            "name" => "{$user->first_name} {$user->last_name}",
            "document" => $document,
            "document_type" => (strlen($document) > 10 ? "CNPJ" : "CPF"),
            "zipcode" => str_replace("-", "", $compliance->address_zipcode),
            "country_id" => 30,
            "state" => $compliance->address_state,
            "city" => $compliance->address_city,
            "district" => $compliance->neighborhood,
            "street" => $compliance->address,
            "number" => $compliance->address_number,
            "email" => $user->email,
            "phone" => $compliance->phone,
            "mobilephone" => $compliance->phone
        );

        $sale = $this->createBoletoTransaction($user, $transactionTotal, $entityId, $refId, $dueDate, $customer, $instructions =null);

        $paymentId = $sale->getPayment()->getPaymentId();
        $boletoURL = $sale->getPayment()->getUrl();
        $barcode = $sale->getPayment()->getBarCodeNumber();
        $digitableLine = $sale->getPayment()->getDigitableLine();
        
        $deposit = Deposits::where("trx", $deposit->trx)->first();
        
        $deposit->boleto_transaction_id = $paymentId;
        $deposit->boleto_url = $boletoURL;
        $deposit->boleto_barcode = $barcode;
        $deposit->boleto_digitable_line = $digitableLine;
        $deposit->save();
        
        return $deposit;
    }
    
    public function createBoletoTransaction($user, $transactionTotal, $entityId, $refId, $dueDate, $customer, $instructions =null) {
        
        if ($instructions == null) {
            $instructions = "";
        }

        $environment = $this->getEnviroment();
        
        $merchant = new Merchant($this->gateway->val1, $this->gateway->val2);
        
        $sale = new Sale($refId);

        $country = \App\Models\Country::where("id", $customer->country_id)->first();
        $compliance = \App\Models\Compliance::where("user_id", $user->id)->first();
        
        // Crie uma instância de Customer informando o nome do cliente
        $cust = $sale->customer($customer->name)
                  ->setIdentity(str_replace(Array(".", "-", "/"), "", $customer->document))
                  ->setIdentityType($customer->document_type)
                  ->address()->setZipCode(str_replace("-", "", $customer->zipcode))
                             ->setCountry($country->iso3)
                             ->setState($customer->state)
                             ->setCity($customer->city)
                             ->setDistrict($customer->district)
                             ->setStreet($customer->street)
                             ->setNumber($customer->number);


        $document = "";
        if (!empty($compliance->tax_id)) {
            $document = $compliance->tax_id;
        } else if (!empty($compliance->document)) {
            $document = $compliance->document;
        } else if (!empty($compliance->reg_no)) {
            $document = $compliance->reg_no;
        }

        if (empty($document)) {
            throw new \Exception("Você precisa fazer o compliance para poder recarregar com Boleto.");
        }

        // Crie uma instância de Payment informando o valor do pagamento
        $payment = $sale->payment(number_format($transactionTotal, 2, "", ""))
                ->setType(Payment::PAYMENTTYPE_BOLETO)
                ->setAddress($customer->name)
                ->setBoletoNumber($entityId)
                ->setAssignor($compliance->trading_name)
                ->setDemonstrative("Cobrança Recarga No. {$entityId}")
                ->setExpirationDate($dueDate->format("d/m/Y"))
                ->setIdentification($document)
                ->setInstructions($instructions);
        
        // Crie o pagamento na Cielo
        try {
            
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

            // O token gerado pode ser armazenado em banco de dados para vendar futuras
            //$token = $sale->getPayment()->getCreditCard()->getCardToken();
            
            return $sale;
            
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();
            if ($error != null) {
                throw new \Exception("{$error->getCode()} - {$error->getMessage()}");
            } else {
                throw new \Exception($e->getMessage());
            }
        } 
        
    }
}
