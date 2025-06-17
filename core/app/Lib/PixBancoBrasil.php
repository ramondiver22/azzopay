<?php


namespace App\Lib;

class PixBancoBrasil {
    private $private_key = '';
    private $public_key = '';
    private $apiKey = '';
    private $sandbox = false;
    private $defaultKey = "43427572000164";
    

    public function Setup($private_key, $public_key, $apiKey, $isSandbox = false) {
        $this->private_key = $private_key;
        $this->public_key = $public_key;
        $this->apiKey = $apiKey;
        $this->sandbox = $isSandbox;
    }
    
    private function getPrivateKey() {
        if ($this->sandbox) {
            return "eyJpZCI6IjM2NzI3ZDktNjQzZC00NmIwLTlmYWQtYzViYTNjM2JkNTkyNzQ5ZmVkM2QiLCJjb2RpZ29QdWJsaWNhZG9yIjowLCJjb2RpZ29Tb2Z0d2FyZSI6NDA0MjIsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxLCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MSwiYW1iaWVudGUiOiJob21vbG9nYWNhbyIsImlhdCI6MTY1ODgzOTM4NzcwMX0";
        } else {
            return "eyJpZCI6ImMyMjBlNGUtZjMwNi00OWZhLTgwNDctMGFlMzVhNzYxOTVlZjVjM2UiLCJjb2RpZ29QdWJsaWNhZG9yIjowLCJjb2RpZ29Tb2Z0d2FyZSI6MzA4MjYsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxLCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MSwiYW1iaWVudGUiOiJwcm9kdWNhbyIsImlhdCI6MTY1ODkxNjc1ODA1NX0";
        }
    }
    
    private function getPublicKey() {
        if ($this->sandbox) {
            return "eyJpZCI6IjYxM2M2YzgtY2Y5Mi00MDQ5LTg5NTUtN2ViNSIsImNvZGlnb1B1YmxpY2Fkb3IiOjAsImNvZGlnb1NvZnR3YXJlIjo0MDQyMiwic2VxdWVuY2lhbEluc3RhbGFjYW8iOjF9";
        } else {
            return "eyJpZCI6ImUiLCJjb2RpZ29QdWJsaWNhZG9yIjowLCJjb2RpZ29Tb2Z0d2FyZSI6MzA4MjYsInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxfQ";
        }
    }
    
    private function getApiKey() {
        if ($this->sandbox) {
            return "d27b877903ffab001366e17d00050056b9a1a5b0";
        } else {
            return "7091c08b05ffbe001361e181f0050f56b911a5b7";
        }
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
        //exit(print_r($body));
        return $this->doRequest($method, $path, $body);
    }
    
    
    public function consultarCobranca($txid) {
        $path = "cob/{$txid}";
        $body = Array();
        $method = "GET";
        return $this->doRequest($method, $path, $body);
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

            curl_setopt_array($curl, array(
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
                'Authorization: Basic ' . base64_encode($this->getPublicKey() . ":" . $this->getPrivateKey()),
                'Content-Type: application/x-www-form-urlencoded'
              ),
            ));

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
            
            if (isset ($object->error)){
                throw new \Exception(json_encode($object));
            }
            
            return $object->access_token;
            
        } catch (\Exception $ex) {
            throw new \Exception($ex);
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

            $curl = curl_init();

            curl_setopt_array($curl, $opt);

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
            throw new \Exception($ex);
        } finally {
            if ($curl != null) {
                curl_close($curl);
            }
        }
        
    }
}