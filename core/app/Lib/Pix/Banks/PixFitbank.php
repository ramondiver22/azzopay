<?php

namespace App\Lib\Pix\Banks;

use App\Lib\Pix\Interfaces\IPix;

class PixFitbank implements IPix{

    private $password;
    private $username;
    private $sandbox;
    private $partnerId;
    private $businessUnitId;
    private $bankCode;
    private $bankBranch;
    private $bankAccount;
    private $bankAccountDigit;
    private $pixKey;
    private $pixKeyType;
    private $accountName;
    private $taxNumber;
    private $address;

    private $log;
    
    
    const STATUS_MESSAGE = Array(
        "Created" => Array( "status"=> "Created", "message" => "Solicitação recebida.", "code" =>  0 ),
        "Registering" => Array( "status"=> "Registering", "message" => "Cobrança em processamento no banco central.", "code" =>  0 ),
        "Processed" => Array( "status"=> "Processed", "message" => "QRCode gerado com sucesso!", "code" =>  0 ),
        "Settled" => Array( "status"=> "Settled", "message" => "Cobrança paga com sucesso!", "code" =>  1 ),
        "Error" => Array( "status"=> "Error", "message" => "Erro no processamento da cobrança no banco central.", "code" =>  2 ),
        "Canceled" => Array( "status"=> "Canceled", "message" => "Cobrança cancelada.", "code" =>  2 ),
        "Expired" => Array( "status"=> "Expired", "message" => "QRCode expirado.", "code" =>  2 )
    );
    
    const PAYMENT_STATUS = Array(
        "Created" => Array("message" => "Created PaymentOrder", "code" => 0),
        "Registered" => Array("message" => "Registered after transactionvalidations", "code" => 0),
        "Settled" => Array("message" => "Payment concluded", "code" => 1),
        "Paid" => Array("message" => "Payment concluded", "code" => 1),
        "Canceled" => Array("message" => "Payment canceled", "code" => 2)
    );

    public function __construct($username, $password, $params, $isSandbox = false) {
        $this->username = $username;
        $this->password = $password;
        $this->sandbox = $isSandbox;

        if (is_string($params)) {
            $params = json_decode($params);
        }
        
        /*
        {
            "partnerId": "1459",
            "businessUnitId": "2317",
            "bankCode": "",
            "bankBranch": "",
            "bankAccount": "",
            "bankAccountDigit": "",
            "pixKey": "",
            "pixKeyType": "",
            "accountName": "",
            "taxNumber": "49775544000114",
            "address": {
                "line1": "",
                "line2": "",
                "zipcode": "",
                "neighborhood": "",
                "cityCode": "",
                "cityName": "",
                "state": "",
                "addressType": "",
                "country" : "",
                "complement": "",
                "reference": ""
            }
        }
        */
       
        $this->partnerId = (isset($params->partnerId) ? $params->partnerId : null);
        $this->businessUnitId = (isset($params->partnerId) ? $params->businessUnitId : null);
        $this->bankCode = (isset($params->partnerId) ? $params->bankCode : null);
        $this->bankBranch = (isset($params->partnerId) ? $params->bankBranch : null);
        $this->bankAccount = (isset($params->partnerId) ? $params->bankAccount : null);
        $this->bankAccountDigit = (isset($params->partnerId) ? $params->bankAccountDigit : null);
        $this->pixKey = (isset($params->partnerId) ? $params->pixKey : null);
        $this->pixKeyType = (isset($params->partnerId) ? $params->pixKeyType : null);
        $this->accountName = (isset($params->accountName) ? $params->accountName : null);
        $this->taxNumber = (isset($params->taxNumber) ? $params->taxNumber : null);
        $this->address = (isset($params->address) ? $params->address : (Object) Array(
            "line1" => null,
            "line2" => null,
            "zipcode" => null,
            "neighborhood" => null,
            "cityCode" => null,
            "cityName" => null,
            "state" => null,
            "addressType" => null,
            "country" => null,
            "complement" => null,
            "reference" => null
        ));

    }

    private function getPixKeyTypeCode($pixKeyType) {
        
        $codes = Array("cpf" => 0, "cnpj" => 1, "email" => 2, "evp" => 4, "phone" => 3);
        if (!isset($codes[strtolower($pixKeyType)])) {
            throw new \Exception("Tipo de chave pix inválida.");
        }
        return $codes[strtolower($pixKeyType)];
    }

    public function criarCobranca($user, $value, $key = null, $description = null)  {

        if (is_array($user)) {
            $user = (object) $user;
        }
        
        $dueDate = new \DateTime(date("Y-m-d H:i:s"));
        $dueDate->add(new \DateInterval("P1D"));
        $name = (isset($user->name) ?  $user->name : ($user->first_name . " " . $user->last_name));
        
        $body = Array(
            "Method" => "GenerateDynamicPixQRCodeDueDate",
            "PartnerId" => $this->partnerId,
            "BusinessUnitId" => $this->businessUnitId,
            "PixKey" => $this->pixKey,
            "TaxNumber" => $this->taxNumber,
            "PayerTaxNumber" => str_replace(Array("-", "/", "."), "", $user->document),
            "PayerName" => $name,
            "PrincipalValue" => number_format($value, 2, ".", ""),
            "DueDate" => $dueDate->format("Y-m-d"),
            "ExpirationDate" => $dueDate->format("Y-m-d"),
            "Address" => Array(
                "AddressLine" => $this->address->line1,
                "AddressLine2" => $this->address->line2,
                "ZipCode" => $this->address->zipcode,
                "Neighborhood" => $this->address->neighborhood,
                //"CityCode" => $this->address->cityCode,
                "CityName" => $this->address->cityName,
                "State" => $this->address->state,
                "AddressType" => $this->address->addressType,
                "Country" => $this->address->country,
                "Complement" => $this->address->complement,
                "Reference" => $this->address->reference
            ),
            "ChangeType" => 0, // nao permite alteracao do valor
            "Bank" => $this->bankCode,
            "BankBranch" => $this->bankBranch,
            "BankAccount" => $this->bankAccount,
            "BankAccountDigit" => $this->bankAccountDigit
        );

        $path = "GenerateDynamicPixQRCodeDueDate";
        $method = "POST";
        $result = $this->doRequest($method, $path, $body);

        sleep(3);
        $order = $this->consultarCobranca($result->DocumentNumber);
         
        if ($order->status == 2) {
            throw new \Exception("{$order->statusEnum} - {$order->statusDescription}");
        }
        
        return Array(
            "qrcode" => $order->qrcode,
            "copy" => $order->copy,
            "txid" => $order->txid
        );

    }

    public function criarCobrancaQrCode(array $user, $value, $key = null, $description = null) {
        return $this->criarCobranca($user, $value, $key, $description);
    }

    public function consultarCobranca($txid) {
        $body = Array(
            "Method" => "GetPixQRCodeById",
            "PartnerId" => $this->partnerId,
            "BusinessUnitId" => $this->businessUnitId,
            "DocumentNumber" => $txid,
            "TaxNumber" => $this->taxNumber
        );


        $path = "";
        $method = "POST";
        $result =  $this->doRequest($method, $path, $body);

        $statusMessage = $this->getMessageStatus($result->GetPixQRCodeByIdInfo->Status);
        
        /*
        {
            "Success": "true",
            "GetPixQRCodeByIdInfo": {
                "Identifier": null,
                "PixQRCodeType": "DynamicDueDate",
                "PixKey": "49775544000114",
                "Status": "Registering",
                "City": "Fortaleza",
                "ZipCode": "60731305",
                "PayerName": "Vagner Fogaca Carvalho",
                "PayerTaxNumber": "05070222546",
                "ReceiverName": "G SOLUCOES E CONSULTORIA LTDA",
                "ReceiverTaxNumber": "49775544000114",
                "ChangeType": "None",
                "PrincipalValue": "R$13,45",
                "DueDate": "31/03/2024",
                "RebateValue": null,
                "InterestValue": null,
                "FineValue": null,
                "Reusable": false,
                "PayerRequest": null,
                "ExpirationDate": "31/03/2024 23:59:59",
                "TransactionChangeType": null,
                "TransactionValue": null,
                "TransactionPurpose": null,
                "AgentModality": null,
                "CreationDate": "11/03/2024",
                "QRCodeBase64": " ",
                "HashCode": " ",
                "ImageUrl": "https://billfileapi.fitbank.com.br/api/billfile/qrcode/62BD8FBE7336C16D5F82B2E20A?format=png"
            }
        }
        */

        return (Object) Array(
            "paymentMethod" => "pix",
            "qrcode" => base64_decode($result->GetPixQRCodeByIdInfo->HashCode), //"data:image/png;base64,". $result->GetPixQRCodeByIdInfo->QRCodeBase64,
            "copy" => base64_decode($result->GetPixQRCodeByIdInfo->HashCode),
            "txid" => $txid,
            "status" => $statusMessage["code"],
            "statusEnum" => $result->GetPixQRCodeByIdInfo->Status,
            "statusDescription" => $statusMessage["message"],
            "totalReceived" => str_replace(",", ".", 
                                    str_replace(",", ".", 
                                        str_replace(Array("R$", " "), "", $result->GetPixQRCodeByIdInfo->PrincipalValue)
                                    )
                                ),
            "paymentId" => $txid,
            "brand" => null,
            "json" => json_encode($result),
            "installments" => 1,
            "receivedValue" => str_replace(",", ".", 
                str_replace(",", ".", 
                    str_replace(Array("R$", " "), "", $result->GetPixQRCodeByIdInfo->PrincipalValue)
                )
            ),
            "transactionId" => null,
            "gateway" => "fitbank"
        );


    }
    
    public function sendPayment($receiver, $pixKey, $pixkeyType, $value, $description) {

        $keyType = indentify_pixkey_type( $pixKey );
        if ($keyType == "phone") {
            $pixKey = str_replace(Array(".", "/", "-", "(", ")", " "), "", $pixKey);

            if (strlen() == 11) {
                $pixKey = "+55{$pixKey}";
            } else if (strlen() == 13) {
                $pixKey = "+{$pixKey}";
            }
        }

        $paymentDate = date("Y-m-d");
        $pixkeyData = $this->consultPixKey($pixKey, $keyType);

        $body = Array(
            "Method" => "GeneratePixOut",
            "PartnerId" => $this->partnerId,
            "BusinessUnitId" => $this->businessUnitId,
            "TaxNumber" => $this->taxNumber,
            "Bank" => $this->bankCode,
            "BankBranch" => $this->bankBranch,
            "BankAccount" => $this->bankAccount,
            "BankAccountDigit" => $this->bankAccountDigit,
            "ToTaxNumber" => $pixkeyData->document,
            "ToName" => $pixkeyData->name,
            "ToBank" => $pixkeyData->bank->code,
            "ToISPB" => $pixkeyData->bank->ispb,
            "ToBankBranch" => $pixkeyData->bank->branch,
            "ToBankAccount" => $pixkeyData->bank->account,
            "ToBankAccountDigit" => $pixkeyData->bank->digit,
            "Value" => number_format($value, 2, ".", ""),
            "PixKey"=> $pixkeyData->pixkey,
            "PixKeyType"=> $pixkeyData->pixkeyType,
            "AccountType"=> $pixkeyData->bank->type,
            "Identifier" => "PAYMENT-" . rand(100000, 999999) . time(),
            "PaymentDate" => $paymentDate,
            "SearchProtocol" => $pixkeyData->protocol,
            "Description" => $description
        );

        $path = "";
        $method = "POST";
        $obj = $this->doRequest($method, $path, $body);

        /*
        {
            "Success": "true",
            "Message": "Solicitação para pagamento PIX recebida com sucesso.",
            "DocumentNumber": 7728242,
            "Url": "https://sandboxreceipt.fitbank.com.br/receipt/pdf?filename=2024-03-11/fl3psa40.pdf"
        }
        */

        $paymentOrder = $this->consultarPagamento($obj->DocumentNumber);

        /*
        return Array(
            "txid" => $paymentOrder->PaymentOrderId,
            "payerName" => $paymentOrder->PaymentOrderId,
            "document" => $paymentOrder->TaxNumber,
            "status" => ($paymentOrder->Status == "Settled" ? 1 : 2),
            "paymentDate" => str_replace("/", "-", $paymentOrder->PaymentDate),
            "msg" => $paymentOrder->ReasonCode
        );
        */

        if ($paymentOrder["status"] == 2) {
            
            throw new \Exception($paymentOrder["msg"]);
        }

        return (object) Array(
            "txid" => $obj->DocumentNumber,
            "payerName" => $paymentOrder["payerName"],
            "document" => $paymentOrder["document"],
            "status" => $paymentOrder["status"],
            "paymentDate" => $paymentOrder["paymentDate"],
            "msg" => $paymentOrder["msg"],
            "endtoend" => ""
        );
    }
    

    public function listarCobrancas($page, $itensPage, $statusFilter, $dateFrom, $dateTo) {
        throw new \Exception("Fitbank - listarCobrancas não implementado.");
    }

    public function listarPagamentos($page, $statusFilter, $dateFrom, $dateTo){
        $body = Array(
            "Method" => "GetPixOutByDate",
            "PartnerId" => $this->partnerId,
            "BusinessUnitId" => $this->businessUnitId,
            "TaxNumber" => $this->taxNumber,
            "Bank" => $this->bankCode,
            "BankBranch" => $this->bankBranch,
            "BankAccount" => $this->bankAccount,
            "BankAccountDigit" => $this->bankAccountDigit,
            "StartDate" => $dateFrom,
            "EndDate" => $dateTo,
            "PageIndex" => $page,
            "PageSize" => 10
        );


        $path = "";
        $method = "POST";
        return $this->doRequest($method, $path, $body);
    }


    public function consultPixKey($pixkey, $pixkeyType) {

        $body = Array(
            "Method" => "GetInfosPixKey", 
            "PartnerId" => $this->partnerId,  
            "BusinessUnitId" => $this->businessUnitId,
            "PixKey" => $pixkey,
            "PixKeyType" => $pixkeyType,
            "TaxNumber" => $this->taxNumber
        );

        $path = "";
        $method = "POST";
        $obj = $this->doRequest($method, $path, $body);

        /*
        {
            "Success": "true",
            "Message": "ISI0001 - Método executado com sucesso",
            "SearchProtocol": 165442,
            "Infos": {
                "ReceiverBankName": "Fitbank Pagamentos Eletronicos",
                "ReceiverName": "G SOLUCOES E CONSULTORIA LTDA",
                "ReceiverISPB": "13203354",
                "ReceiverBank": "450",
                "ReceiverBankBranch": "1",
                "ReceiverBankAccount": "17820451",
                "ReceiverBankAccountDigit": "1",
                "ReceiverAccountType": "0",
                "PixKeyType": "1",
                "PixKeyValue": "49775544000114",
                "ReceiverTaxNumber": "49775544000114"
            }
        }
        */

        return (Object) Array(
            "protocol" => $obj->SearchProtocol,
            "bank" => (Object) Array(
                "code" => $obj->Infos->ReceiverBank,
                "name" => $obj->Infos->ReceiverBankName,
                "ispb" => $obj->Infos->ReceiverISPB,
                "branch" => $obj->Infos->ReceiverBankBranch,
                "account" => $obj->Infos->ReceiverBankAccount,
                "digit" => $obj->Infos->ReceiverBankAccountDigit,
                "type" => $obj->Infos->ReceiverAccountType,
            ),
            "name" => $obj->Infos->ReceiverName,
            "document" => $obj->Infos->ReceiverTaxNumber,
            "pixkey" => $obj->Infos->PixKeyValue,
            "pixkeyType" => $obj->Infos->PixKeyType
        );

    }
    

    public function consultarPagamento($transactionId) {
        $body = Array(
            "Method" => "GetPixOutById",
            "PartnerId" => $this->partnerId,
            "BusinessUnitId" => $this->businessUnitId,
            "DocumentNumber" => $transactionId,
            "TaxNumber" => $this->taxNumber,
            "Bank" => $this->bankCode,
            "BankBranch" => $this->bankBranch,
            "BankAccount" => $this->bankAccount,
            "BankAccountDigit" => $this->bankAccountDigit
        );
         
        $path = "";
        $method = "POST";
        $obj = $this->doRequest($method, $path, $body);

        /*
        {
            "Success": "true",
            "Message": "Método executado com sucesso.",
            "Infos": {
                "DocumentNumber": 7728242,
                "Identifier": "PAYMENT-00001",
                "EndToEndId": "E3546656543843499649703149277748",
                "FromName": "G SOLUCOES E CONSULTORIA LTDA",
                "FromTaxNumber": "49775544000114",
                "FromISPB": "13203354",
                "FromBankCode": "450",
                "FromBankBranch": "0001",
                "FromBankAccount": "17820451",
                "FromBankAccountDigit": "1",
                "ToName": "G SOLUCOES E CONSULTORIA LTDA",
                "ToTaxNumber": "49775544000114",
                "ToISPB": "13203354",
                "ToBankCode": "450",
                "ToBankBranch": "0001",
                "ToBankAccount": "17820451",
                "ToBankAccountDigit": "1",
                "Status": "Settled",
                "ErrorDescription": null,
                "ErrorCode": null,
                "RateValue": 0.00,
                "TotalValue": 1.45,
                "ReceiptUrl": "https://sandboxreceipt.fitbank.com.br/receipt/pdf?filename=2024-03-11/fl3psa40.pdf",
                "PaymentDate": "11/03/2024 21:50:07",
                "Tags": []
            }
        }
        */

        return Array(
            "txid" => $obj->Infos->DocumentNumber,
            "payerName" => $obj->Infos->ToName,
            "document" => $obj->Infos->ToTaxNumber,
            "status" => $this->getPaymentStatus($obj->Infos->Status),
            "paymentDate" => str_replace("/", "-", $obj->Infos->PaymentDate),
            "msg" => ($obj->Infos->ErrorDescription != null ? $obj->Infos->ErrorDescription : ""),
            "endtoend" => ''
        );

    }

    
    private function getConvertToTransactionStatus($status) {

        
        switch($status) {
            case 0: return 0;
            case 1: return 0;
            case 3: return 0;
            case 6: return 0;
            case 7: return 2;
            case 8: return 2;
            case 9: return 1;
            case 11: return 0;
            case 12: return 2;
        }
    
    }

    private function getPaymentStatus($status) {
        return self::PAYMENT_STATUS[$status];
    }
    private function getMessageStatus($status) {
        return self::STATUS_MESSAGE[$status];
    }


    private function getUsername() {
        return $this->username;
    }
    
    private function getPassword() {
        return $this->password;
    }
    
    public function setSandbox($isSandbox) {
        $this->sandbox = $isSandbox;
    }
	
    
    private function getHost() {
        if ($this->sandbox) {
            return "https://sandboxapi.fitbank.com.br/main/execute";
        } else {
            return "https://apiv2.payments.fitbank.com.br/main/execute";
        }
    }
    

    private function getBasic() {
        return base64_encode("{$this->username}:{$this->password}");
    }

    private function doRequest($method, $path, $body) {
        $curl = null;
        try {
            if ($body == null) {
                $body = Array();
            }

            $url = $this->getHost() . (empty($path) ? "" : "/{$path}");

            $headers = Array(
                'Accept: application/json',
                'Authorization: Basic ' . $this->getBasic()
            );

            $opt = Array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($method)
            );
            
            $jsonBody = null;
            if (in_array(strtoupper($method), Array("PUT", "POST", "PATCH"))) {
                $jsonBody = json_encode($body);
                $opt[CURLOPT_POSTFIELDS] = $jsonBody;
                $headers[] = 'Content-Type: application/json';
            } else if (in_array(strtoupper($method), Array("GET"))){
                $url .= "&" . http_build_query($body);
            }

            $opt[CURLOPT_URL] = $url;

            $opt[CURLOPT_HTTPHEADER] = $headers;

            $curl = curl_init();

            //print_r($opt);
            curl_setopt_array($curl, $opt);

            $response = curl_exec($curl);


            $info = curl_getinfo($curl);

            $this->registerLog($url, $headers, $jsonBody, $response, $info);

            //echo $response;
            if (empty($response)) {
                throw new \Exception("Resposta vazia do servidor.");
            }
            
            if (curl_errno($curl) > 0) {
                throw new \Exception(curl_error($curl));
            }
            
            $object = json_decode($response);
            


            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception(json_last_error() . ": " . json_last_error_msg());
            }
            

            if (!$object->Success || $object->Success == "false") {
                if (isset($object->Message)) {
                    throw new \Exception($object->Message);
                } else {
                    throw new \Exception($response);
                }
                
            }
            
            return $object;
            
        } catch(\Exception $ex) {
            throw new \Exception($ex->getMessage());
        } finally {
            if ($curl != null) {
                curl_close($curl);
            }
        }
        
    }



    private function registerLog($hostUrl, $headers, $jsonBody, $response, $info) {
        
        foreach($headers as $key=>$h) {
            if (strpos(strtolower($h), "authorization") !== false) {
                unset($headers[$key]);
            }
        }

        if (!file_exists("fitbankcallbacks")) {
            mkdir("fitbankcallbacks");
        }

        if (!file_exists("fitbankcallbacks/calls")) {
            mkdir("fitbankcallbacks/calls");
        }

        $content = " ============================================================================ " . PHP_EOL;
        $content .= " Request " . PHP_EOL;
        $content .= " ============================================================================ " . PHP_EOL . PHP_EOL;
        $content .= "curl --location '{$hostUrl}' ";

        foreach($headers as $header) {
            $content .= " --header '{$header}' ";
        }

        $content .= " --data '{$jsonBody}'";

        $content .=  PHP_EOL . PHP_EOL;
        $content .= " ============================================================================ " . PHP_EOL;
        $content .= " Response " . PHP_EOL;
        $content .= " ============================================================================ " . PHP_EOL . PHP_EOL;

        $content .= $response;


        $content .=  PHP_EOL . PHP_EOL;
        $content .= " ============================================================================ " . PHP_EOL;
        $content .= " Response Headers " . PHP_EOL;
        $content .= " ============================================================================ " . PHP_EOL . PHP_EOL;

        $content .= json_encode($info);


        $arquivo = fopen("fitbankcallbacks/calls/" . time() . '.txt','w'); 
        if ($arquivo == false) {
            //die('Não foi possível criar o arquivo.');
        }
        
        fwrite($arquivo, $content);
        
        fclose($arquivo);

    }
}