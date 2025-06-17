<?php

namespace App\Lib\Pix\Banks;

use App\Lib\Pix\Interfaces\IPix;

class PixBancoBrasil implements IPix{

    private $private_key = '';
    private $public_key = '';
    private $apiKey = '';
    private $sandbox = false;
    private $defaultKey = null;

    public function __construct($private_key, $public_key, $apiKey, $defaultKey, $isSandbox = false) {
        $this->private_key = $private_key;
        $this->public_key = $public_key;
        $this->apiKey = $apiKey;
        $this->sandbox = $isSandbox;
        $this->defaultKey = $defaultKey;
    }
    
    private function getPrivateKey() {
        return $this->private_key;
    }
    
    private function getPublicKey() {
        return $this->public_key;
    }
    
    private function getApiKey() {
        return $this->apiKey;
    }
    
    public function setSandbox($isSandbox) {
        $this->sandbox = $isSandbox;
    }
	
    public function criarCobranca($user, $value, $key = null, $description = null) {
        $path = "cob";
        
        $body = Array(
            "calendario" => Array(
              "expiracao" => "36000"
            ),
            "devedor" => Array(
              "cpf" => str_replace(Array("-", ".", "/"), "", $user->document),
              "nome" => $user->first_name . " " . $user->last_name
            ),
            "valor" => Array(
              "original" => number_format($value, 2, ".", "")
            ),
            "chave" => ($key != null ? $key : $this->defaultKey),
            "solicitacaoPagador" => ($description != null ? $description : "")
        );
        $method = "PUT";
        
        return $this->doRequest($method, $path, $body);
    }
    
    public function criarCobrancaQrCode(array $user, $value, $key = null, $description = null) {
        $path = "cobqrcode";
        
        $body = Array(
            "calendario" => Array(
              "expiracao" => "36000"
            ),
            "devedor" => Array(
              "cpf" => (!empty($user["document"]) ? str_replace(Array("-", ".", "/"), "", $user["document"]) : ""),
              "nome" => (!empty($user["name"]) ? $user["name"] : ""),
            ),
            "valor" => Array(
              "original" => number_format($value, 2, ".", "")
            ),
            "chave" => ($key != null ? $key : $this->defaultKey),
            "solicitacaoPagador" => ($description != null ? $description : "")
        );
        $method = "PUT";
        
        $result = $this->doRequest($method, $path, $body);

        return Array(
            "qrcode" => $result->textoImagemQRcode,
            "txid" => $result->txid,
            "copy" => $result->textoImagemQRcode
        );
    }
    
    
    public function consultarCobranca($txid) {
        $path = "cob/{$txid}";
        $body = Array();
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
        throw new \Exception("Não implementado.");
    }

    public function sendPayment($userData, $pixkey, $pixkeyType, $amount, $description) {
        throw new \Exception("Este provedor de pagamentos não oferece o serviço de saque.");
    }
    
    public function consultarPagamento($txid) {
        throw new \Exception("Este provedor de pagamentos não oferece o serviço de saque.");
    }

    /**
     * 
     * @var $page
     * @var $itensPage
     * @var $statusFilter TODOS = 0, PAGO = 1, PENDENTE = 2, EXPIRADO = 3
     */
    public function listarPagamentos($page, $statusFilter, $dateFrom, $dateTo) {
        throw new \Exception("Não implementado.");
    }


    
    private function getHost() {
        if ($this->sandbox) {
            return "https://api.hm.bb.com.br/pix/v1/";
        } else {
            return "https://api.bb.com.br/pix/v1/";
        }
    }
    
    private function getOauthHost() {
        if ($this->sandbox) {
            return "https://oauth.hm.bb.com.br/oauth/token";
        } else {
            return "https://oauth.bb.com.br/oauth/token";
        }
    }
    
    private function getBearer() {
        
        $curl = null;
        try {
            $curl = curl_init();

            $opt = array(
                CURLOPT_URL => $this->getOauthHost(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=cob.read cob.write pix.read pix.write',
                CURLOPT_HTTPHEADER => array(
                  'Authorization: Basic ' . base64_encode(trim($this->getPublicKey()) . ":" . trim($this->getPrivateKey())),
                  'Content-Type: application/x-www-form-urlencoded'
                ),
            );

            //print_r($opt);
            curl_setopt_array($curl, $opt);

            $response = curl_exec($curl);

            
            if (empty($response)) {
                throw new \Exception("Resposta vazia do servidor.");
            }
            
            if (curl_errno($curl) > 0) {
                throw new \Exception(curl_error($curl));
            }
            
            //echo $response;
            $object = json_decode($response);
            
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception(json_last_error() . ": " . json_last_error_msg());
            }
            
            if (isset($object->error)){
                throw new \Exception(json_encode($object));
            }
            
            return $object->access_token;
            
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        } finally {
            if ($curl != null) {
                curl_close($curl);
            }
        }
    }
        
    private function doRequest($method, $path, $body) {
        $curl = null;
        try {
            if ($body == null) {
                $body = Array();
            }

            $url = $this->getHost() . $path . "/?gw-dev-app-key={$this->getApiKey()}";

            $headers = Array(
                'Accept: application/json',
                'Authorization: Bearer ' . $this->getBearer()
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
                $url .= "&" . http_build_query($body);
            }

            $opt[CURLOPT_URL] = $url;

            $opt[CURLOPT_HTTPHEADER] = $headers;

            //print_r($opt);
            $curl = curl_init();

            curl_setopt_array($curl, $opt);

            //echo $response;
            $response = curl_exec($curl);
           
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
            
            if (isset($object->erros)) {
                $msg = "";
                foreach ($object->erros as $error) {
                    $msg .= $error->mensagem . " ";
                }
                
                throw new \Exception($msg);
            } else if (isset ($object->error)){
                throw new \Exception($object->message);
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