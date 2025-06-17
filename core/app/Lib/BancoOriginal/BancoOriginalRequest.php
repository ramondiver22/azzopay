<?php

namespace App\Lib\BancoOriginal;

abstract class BancoOriginalRequest {

    private $homologacao = true;
    private $clientId = null;
    private $secretKey = null;
    private $urlCallback = null;
    
    public function __construct() {
        
        $gateway = BancoOriginalGateway::getOriginalGateway();
        
        if ($gateway == null) {
            throw new Exception("Gateway config not set for Original Bank.");
        }
        
        if (empty($gateway->val1)) {
            throw new Exception("Client ID not set for Original Bank.");
        }
        
        if (empty($gateway->val2)) {
            throw new Exception("Secret Key not set for Original Bank.");
        }
        if (empty($gateway->val3)) {
            throw new Exception("Callback URL not set for Original Bank.");
        }
        
        $this->clientId = $gateway->val1;
        $this->secretKey = $gateway->val2;
        $this->urlCallback = $gateway->val3;
        $this->homologacao = ($gateway->sandbox > 0);
    }
    
    protected function getUrlCallback() {
        return $this->urlCallback;
    }


    protected function getClientId() {
        return $this->clientId;
    }
    
    protected  function getSecretkey() {
        return $this->secretKey;
    }
    
    protected function getBasicAuth() {
        return base64_encode("{$this->clientId}:{$this->secretKey}");
    }

    protected function getHost() {
        if ($this->homologacao) {
            return 'https://hml-api.openbanking.com.br/';
        } else {
            return "https://api.openbanking.com.br/";
        }
    }

    protected function makeRequest($method, $path, $body, $params = null, $headers = null) {

        try {
            $curl = curl_init();

            if ($headers == null) {
                $headers = Array();
            }

            $authToken = BancoOriginalAuth::getValidToken();
            
            $headers[] = 'Authorization: Bearer ' . $authToken->access_token;
            $host = $this->getHost() . $path;

            if ($params != null && sizeof($params) > 0) {
                $path .= "?" . http_build_query($params);
            }

            $opt = array(
                CURLOPT_URL => $host,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($method)
            );

            if (sizeof($body) > 0) {
                $headers[] = "Content-Type: application/json";
                $opt[CURLOPT_POSTFIELDS] = json_encode($body);
            }

            $opt[CURLOPT_URL] = $host;
            $opt[CURLOPT_HTTPHEADER] = $headers;

            //print_r($opt);
            curl_setopt_array($curl, $opt);

            $response = curl_exec($curl);

            //echo $response;
            $info = curl_getinfo($curl);

            $httpCode = $info["http_code"];

            $object = json_decode($response);
            
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception(json_last_error() . " - " . json_last_error_msg());
            }
            
            if (isset($object->error)) {
                
                $status = (isset($object->status) ?$object->status . " - " : "");
                $error = (isset($object->error) ?$object->error  : "");
                $message = (isset($object->message) ? ": " . $object->message  : "");

                $msg = "{$status} {$error} {$message}.";
                
                if (isset($object->errorDetails)) {
                    
                    if (is_array($object->errorDetails)) {
                        foreach ($object->errorDetails as $errorObj) {
                            $uniqueId = (isset($errorObj->uniqueId) ? "Unique ID: ". $errorObj->uniqueId . " - " : "");
                            $msg .= " {$uniqueId} {$errorObj->message}.";
                        }
                    } else {
                        $uniqueId = (isset($object->errorDetails->uniqueId) ? "Unique ID: ". $object->errorDetails->uniqueId . " - " : "");
                        $msg .= " {$uniqueId} {$object->errorDetails->message}.";
                    }
                    
                    
                }
                throw new \Exception($msg);
            } else {
                if (!in_array($httpCode, Array("200", "201", "202", "203", "204"))) {
                    if (isset($object->message)) {
                        throw new \Exception($object->message);
                    } else {
                        throw new \Exception("Erro desconhecido.");
                    }
                }
            }
            
            return  $object;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        } finally {
            if (isset($curl) && $curl != null) {
                curl_close($curl);
            }
        }
    }

}


