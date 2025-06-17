<?php

namespace App\Lib\BancoOriginal;

use App\Models\User;
use App\Models\Compliance;

class BancoOriginalAccount extends BancoOriginalRequest {
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function createAccount($compliance, $bankData) {
        
        $staff = 0;
        switch ($compliance->staff_size) {
            case "1-5": $staff = 5; break;
            case "5-50": $staff = 50; break;
            case "50+": $staff = 51; break;
        }
        

        if (empty($bankData["bank"])) {
            throw new \Exception("É necessário informar o código do banco.");
        }
        if (empty($bankData["branch"])) {
            throw new \Exception("É necessário informar o número da agência.");
        }
        if (empty($bankData["account"])) {
            throw new \Exception("É necessário informar o número da conta.");
        }

        if ($compliance->status_personal != "APPROVED") {
            throw new \Exception("Você precisa ter seu perfil pessoal e empresarial aprovados pelo compliance.");
        }
        if ($compliance->status_business != "APPROVED") {
            throw new \Exception("Você precisa ter seu perfil pessoal e empresarial aprovados pelo compliance.");
        }

        $user = User::where("id", $compliance->user_id)->first();

        $phone = str_replace(Array("(", ")", "-", " "), "", $compliance->phone);
        $ddd = substr($phone, 0,2);
        $phoneNumber = substr($phone, 2);
        
        $originalAddress = $this->getAddress($compliance->address_zipcode);
        //exit(print_r($address));
        $path = "opening-accounts-partners/v1/accounts-register";
        $method = "POST";
        $body = Array(
            "companyDocumentNumber" => str_replace(Array(".", "/", "-"), "", $compliance->company_document_id),
            "monthRevenue" => (double)$compliance->month_revenue,
            "patrimony" => (double)$compliance->patrimony,
            "numberOfEmployees" => (int) $staff,
            "optIn" => true,
            "optInKeyPIX" => true,
            "fee" => (double) $user->original_hub_pix_fee,
            "representatives" => Array (
                Array(
                    "isPrincipal" => true,
                    "documentNumber" => $compliance->cpf,
                    "birthDate" => $compliance->birthday,
                    "name" => "{$compliance->first_name} {$compliance->last_name}",
                    "motherName" => $compliance->mother_name,
                    "addresses" => Array(
                        Array(
                            "isPrincipal" => true,
                            "street" => $compliance->address,
                            "type" => "RESIDENCIAL",
                            "number" => (int)$compliance->address_number,
                            "complement" => (!empty($compliance->address_complement) ? $compliance->address_complement : ""),
                            "city" => $compliance->address_city,
                            "cityCode" =>  $originalAddress->cityCode,
                            "neighborhood" => $compliance->neighborhood,
                            "stateCode" => $originalAddress->stateCode,
                            "state" => $compliance->address_state,
                            "zipCode" => str_replace(Array(".", "-"), "", $compliance->address_zipcode)
                        )
                    ),
                    "contacts" => Array(
                      Array(
                        "isPrincipal" => true,
                        "countryCode" => $compliance->mobilephone_country_code,
                        "phoneCode" => $compliance->mobilephone_ddd,
                        "phoneNumber" => $compliance->mobilephone,
                        "phoneType" => $compliance->phone_type,
                        "email" => $compliance->email,
                        "mailType" => "PESSOAL"
                      )
                    )   
                )
            ),
            "paymentInformation" => Array(
                "bank" => (int)$bankData["bank"],
                "branch" => (int)$bankData["branch"],
                "account" => (int)$bankData["account"]
            )
        );
             
        
        $headers = Array(
            "x-transaction-id" => time()
        );
        
        return $this->makeRequest($method, $path, $body, null, $headers);
    }
    
    
    public function getAddress($cep) {
        $path = "addresses/v1/addresses/" . str_replace(Array(".", "-"), "", $cep);
        $method = "GET";
        
        $body = Array();   
        $headers = Array(
        );
        
        $object = $this->makeRequest($method, $path, $body, null, $headers);

        if (sizeof($object->data) > 0) {
            return $object->data[0];
        } else {
            return null;
        }
    }
    
    public function getAccount($orignalHubUUID) {
        $path = "opening-accounts-partners/v1/accounts-register";
        $method = "POST";
        
        $body = Array();   
        $headers = Array(
            "x-transaction-id: " . time(),
            "uuid: " . $orignalHubUUID
        );
        
        return $this->makeRequest($method, $path, $body, null, $headers);
    }
    
    
    public function verify($prospectUUID, $orignalHubUUID, $cpfNumber, $tokenCode) {
        $path = "opening-accounts-partners/v1/accounts-register/verify";
        $method = "POST";
        
        $body = Array(
            "prospectUuid" => $prospectUUID,
            "tokenList" => Array(
              Array(
                "cpfNumber" => str_replace(Array("-", ".", "/"), "", $cpfNumber),
                "tokenCode" => $tokenCode
              )
            ),
            "uuid" => $orignalHubUUID
        );   
        $headers = Array(
            "x-transaction-id: " . time()
        );
        
        return $this->makeRequest($method, $path, $body, null, $headers);
    }
    
    
}