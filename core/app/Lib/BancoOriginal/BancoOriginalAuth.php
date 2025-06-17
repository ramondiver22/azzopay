<?php

namespace App\Lib\BancoOriginal;

use \App\Models\OauthOriginalHubError;
use \App\Models\OauthOriginalHub;

class BancoOriginalAuth extends BancoOriginalRequest {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function grantCode() {
        $opt = Array();
        try {
            
            
            $path = "auth-partner/v1/grant-code";

            $params = Array(
                "response_type" => "code",
                "client_id" => $this->getClientId(),
                "redirect_uri" => $this->getUrlCallback()
            );

            $curl = curl_init();

            $opt = array(
              CURLOPT_URL => $this->getHost() . $path . "?" . http_build_query($params),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            );
            curl_setopt_array($curl, $opt);

            $response = curl_exec($curl);
            
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            //echo "httpcode: {$httpcode}";
            if ($httpcode != 204) {
                $object = json_decode($response);

                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new \Exception(json_last_error() . " - " . json_last_error_msg());
                }

                if (isset($object->error)) {
                    
                    $status = (isset($object->status) ?$object->status . " - " : "");
                    $error = (isset($object->error) ?$object->error  : "");
                    $message = (isset($object->message) ? ": " . $object->message  : "");
                    
                    $msg = "{$status} {$error} {$message}";
                    if (isset($object->errorDetails)) {
                        foreach ($object->errorDetails as $error) {
                            $msg .= " {$error->message}";
                        }
                    }
                    throw new \Exception($msg);
                }
            }
            
        } catch (\Exception $ex) {
            $data = Array(
                "created_at" => date("Y-m-d H:i:s"),
                "error_msg" => $ex->getMessage(),
                "request_info" => json_encode($opt),
            );
            OauthOriginalHubError::create($data);
            
            throw new \Exception($ex->getMessage());
        } finally {
            if (isset($curl) && $curl != null) {
                curl_close($curl);
            }
        }
    }
    
    
    public function accessToken($code) {
        $opt = Array();
        try {
            $path = "auth-partner/v1/access-token";

            $body = Array(
                "grant_type" => "authorization_code",
                "code" => $code
            );

            $curl = curl_init();

            $opt = array(
              CURLOPT_URL => parent::getHost() . $path,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => http_build_query($body),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . parent::getBasicAuth()
              ),
            );
            
            curl_setopt_array($curl, $opt);
            
            $response = curl_exec($curl);
            

            $object = json_decode($response);
            
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception(json_last_error() . " - " . json_last_error_msg());
            }
            
            if (isset($object->error)) {
                    
                $status = (isset($object->status) ?$object->status . " - " : "");
                $error = (isset($object->error) ?$object->error  : "");
                $message = (isset($object->message) ? ": " . $object->message  : "");

                $msg = "{$status} {$error} {$message}";
                if (isset($object->errorDetails)) {
                    foreach ($object->errorDetails as $error) {
                        $msg .= " {$error->message}";
                    }
                }
                throw new \Exception($msg);
            }
            
            $expireAt = new \DateTime(date("Y-m-d H:i:s"));
            $expireAt->add(new \DateInterval("PT{$object->expires_in}S"));
            $data = Array(
                "access_token" => $object->access_token,
                "refresh_token" => $object->refresh_token,
                "token_type" => $object->token_type,
                "expires_seconds" => $object->expires_in,
                "expires_at" => $expireAt->format("Y-m-d H:i:s"),
                "created_at" => date("Y-m-d H:i:s")
            );
            
            OauthOriginalHub::create($data);
            
        } catch (\Exception $ex) {
            $data = Array(
                "created_at" => date("Y-m-d H:i:s"),
                "error_msg" => $ex->getMessage(),
                "request_info" => json_encode($opt),
            );
            OauthOriginalHubError::create($data);
            
            throw new \Exception($ex->getMessage());
        } finally {
            if (isset($curl) && $curl != null) {
                curl_close($curl);
            }
        }
    }
    
    public static function getValidToken() {
        
        $oauthToken = OauthOriginalHub::getCurrentValidToken();
        
        return $oauthToken;
    }
    
}