<?php

namespace App\Lib\Boleto\Banks;

use App\Lib\Boleto\Interfaces\IBoleto;
use App\Lib\BillingUtils;

use Cielo\API30\Merchant;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;

use Cielo\API30\Ecommerce\Request\CieloRequestException;

class BoletoCielo implements IBoleto {

    
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


    public function createTransaction($user, $transactionTotal, $entityId, $refId, $dueDate, $customer, $instructions =null) {
        
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

        $document = BillingUtils::getUserDocument($user->id);

        if (empty($document)) {
            throw new \Exception("Você precisa fazer o compliance para poder recarregar com Boleto.");
        }

        // Crie uma instância de Payment informando o valor do pagamento
        $payment = $sale->payment(number_format($transactionTotal, 2, "", ""))
                ->setType(Payment::PAYMENTTYPE_BOLETO)
                ->setAddress($customer->name)
                ->setBoletoNumber($entityId)
                ->setAssignor(($compliance != null ? $compliance->trading_name : $user->first_name . " " . $user->last_name))
                ->setDemonstrative("Cobrança Recarga No. {$entityId}")
                ->setExpirationDate($dueDate->format("d/m/Y"))
                ->setIdentification($document)
                ->setInstructions($instructions);
        
        // Crie o pagamento na Cielo
        try {
            //exit(print_r($sale));
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

            // O token gerado pode ser armazenado em banco de dados para vendar futuras
            //$token = $sale->getPayment()->getCreditCard()->getCardToken();
            
            return Array(
                "paymentId" => $sale->getPayment()->getPaymentId(),
                "boletoURL" => $sale->getPayment()->getUrl(),
                "barcode" => $sale->getPayment()->getBarCodeNumber(),
                "digitableLine" => $sale->getPayment()->getDigitableLine()
            );
            
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