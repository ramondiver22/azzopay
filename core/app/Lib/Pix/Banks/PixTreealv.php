<?php

namespace App\Lib\Pix\Banks;

use App\Models\GatewayCall;
use App\Lib\Pix\Interfaces\IPix;

class PixTreealv implements IPix{

    private $accessToken = '';
    private $sandbox = false;
    

    public function __construct($accessToken, $isSandbox = false) {
        $this->accessToken = $accessToken;
        $this->sandbox = $isSandbox;
    }
    
    private function getAccessToken() {
        return $this->accessToken;
    }
    
    public function setSandbox($isSandbox) {
        $this->sandbox = $isSandbox;
    }
	
    public function criarCobranca($user, $value, $key = null, $description = null) {
        
        $usr = Array(
            "name" => trim("{$user->first_name} {$user->last_name}"),
            "document" => str_replace(Array("-", ".", "/"), "", $user->document)
        );
        $method = "POST";
        return $this->criarCobrancaQrCode($usr, $value, $key, $description);
    }
    
    public function criarCobrancaQrCode(array $user, $value, $key = null, $description = null) {
        $path = "bank/pixin/dynamic/instant";

        $payerPersonType = "natural";
        $document = (!empty($user["document"]) ? str_replace(Array("-", ".", "/"), "", $user["document"]) : "");
        if (strlen($document) > 11) {
            $payerPersonType = "legal";
        }
        $body = Array(
            "amount" => (double) number_format($value, 2, ".", ""), 
            "expirationSeconds" => 3600,
            "payerName" => (!empty($user["name"]) ? $user["name"] : ""),
            "payerDocumentNumber" => $document,
            "payerPersonType" => $payerPersonType,
            "payerRequest" => null,
            "additionalData" => [
                Array(
                    "key_name" => "descricao",
                    "value" => ($description != null ? $description : "")
                )
            ],
            "urlReturn" => str_replace("\/", "/", str_replace("http:", "https:", route('treealv.callback')))
        );

        $method = "POST";
        
        $result = $this->doRequest($method, $path, $body);

        return Array(
            "qrcode" => $result->data->pixUri,
            "txid" => $result->data->receiverConciliationId,
            "copy" => $result->data->pixUri
        );
    }
    
    
    public function consultarCobranca($txid) {
        $path = "bank/pixin/dynamic/instant";
        $body = Array(
            "receiverConciliationId" => $txid
        );
        $method = "GET";
        return $this->doRequest($method, $path, $body);
    }
    
    /**
     * 
     * @var $page
     * @var $itensPage
     * @var $statusFilter TODOS = 0, PAGO = 1, PENDENTE = 2, EXPIRADO = 3
     */
    public function listarCobrancas($page, $itensPage, $statusFilter, $dateFrom, $dateTo) {
        $path = "bank/pixin/dynamic/instant/list";
        $body = Array(
            "page" => $page,
            "itensPage" => $itensPage,
            "statusFilter" => $statusFilter,
            "dateFrom" => $dateFrom,
            "dateTo" => $dateTo
        );
        $method = "GET";
        return $this->doRequest($method, $path, $body);
    }


    public function sendPayment($userData, $pixkey, $pixkeyType, $amount, $description) {
       
        $typesList = Array("EVP" => "random", "CPF" => "cpf", "CNPJ" => "cnpj", "EMAIL" => "email", "PHONE" => "celphone");

        $pixkeyType = (isset($typesList[strtoupper($pixkeyType)]) ? $typesList[strtoupper($pixkeyType)] : null);
        if (empty($pixkeyType)) {
            throw new \Exception("Tipo de chave PIX inválida.");
        }

        $pixkey = str_replace(Array("(", ")", " ", "-", ".", "/"), "", $pixkey);

        
        if ($pixkeyType == "celphone") {
            if (strlen($pixkey) == 11) {
                $pixkey = "+55{$pixkey}";
            }
        }
        

        $path = "bank/pixout/transfer";
        $body = Array(
            'typeKey' => $pixkeyType,
            'targetKey' => $pixkey,
            'amount' => (double) number_format($amount, 2, ".", ""),
            'description' => $description,
            'urlReturn' => str_replace("\/", "/", str_replace("http:", "https:", route('treealv.callback')))
        );
        $method = "POST";
        $object = $this->doRequest($method, $path, $body);
        return (object) Array(
            "txid" => $object->data->pixTransferKey
        );
    }
    

    public function consultarPagamento($txid) {
        $path = "bank/pixout/transfer";
        $body = Array(
            "pixTransferKey" => $txid
        );
        $method = "GET";
        return $this->doRequest($method, $path, $body);
    }


    /**
     * 
     * @var $page
     * @var $itensPage
     * @var $statusFilter TODOS = 0, PAGO = 1, PENDENTE = 2, EXPIRADO = 3
     */
    public function listarPagamentos($page, $statusFilter, $dateFrom, $dateTo) {
        $path = "bank/pixout/transfer/list";
        $body = Array(
            "page" => $page,
            "statusFilter" => $statusFilter,
            "dateTransactions" => $dateFrom
        );
        $method = "GET";
        return $this->doRequest($method, $path, $body);
    }



    private function getHost() {
        if ($this->sandbox) {
            return "https://bank-service-dot-treeal-development.uc.r.appspot.com/api/v2/";
        } else {
            return "https://bank-service-dot-treealsp.rj.r.appspot.com/api/v2/";
        }
    }
    
        
    private function doRequest($method, $path, $body) {
        $curl = null;
        try {
            if ($body == null) {
                $body = Array();
            }

            $url = $this->getHost() . $path;

            $headers = Array(
                'Accept: application/json',
                'x-access-token: ' . $this->getAccessToken()
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
            
            if (in_array(strtoupper($method), Array("PUT", "POST", "PATCH"))) {
                $opt[CURLOPT_POSTFIELDS] = json_encode($body);
                $headers[] = 'Content-Type: application/json';
            } else if (in_array(strtoupper($method), Array("GET"))){
                $url .= "?" . http_build_query($body);
            }

            $opt[CURLOPT_URL] = $url;

            $opt[CURLOPT_HTTPHEADER] = $headers;

            //print_r($opt);
            $curl = curl_init();

            curl_setopt_array($curl, $opt);

            $response = curl_exec($curl);
            //exit($response);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            try {
                $user = \Illuminate\Support\Facades\Auth::guard('user')->user();
                if (!$user) {
                    $user = \Illuminate\Support\Facades\Auth::user();
                }
                
                GatewayCall::createLog(($user->id ?? null), $url, json_encode($body), $method, $response, $httpcode); 
            } catch (\Exception $ex){

            }

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
            
            /*
            {
                "data": {
                    "errorCode": 400,
                    "errorMessage": "Documento do devedor inválido",
                    "errorTitle": "Validations"
                },
                "message": "9999 - Error Exception, try again later",
                "status": 400
            }
            */
            if (!isset($object->status)) {
                throw new \Exception("Resposta inválida do servidor. Tente novamente.");
            } else if (isset($object->status) || $object->status != 200) {
                $msg = (isset($object->data->errorMessage) ? $object->data->errorMessage : $object->message);
                throw new \Exception($msg);
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
}