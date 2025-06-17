<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\BancoOriginal\BancoOriginalAuth;
use \App\Models\OauthOriginalHub;

class BancoOriginalController extends Controller {
    
    
    public function index(Request $request) {
        
        
        
        exit("Welcome");
    }
    
    public function auth() {
        
        try {
            
            
            $oauthOriginalHub = OauthOriginalHub::orderBy("id", "desc")->first();
            //exit(print_r($oauthOriginalHub));
            $today = new \DateTime(date("Y-m-d H:i:s"));
            
            
            $generate = false;
            
            if ($oauthOriginalHub != null) {
                $validity = new \DateTime(substr($oauthOriginalHub->expires_at, 0, 19));

                $generate = false;
                if ($today->getTimestamp() > $validity->getTimestamp()) {
                    // token venceu, é atualizado
                    $generate = true;
                } else {
                    $interval = $today->diff($validity);
                    // se o tempo de validate do token for inferior ao tempo em que o cron rodará da próxima vez então ele é atualizado.
                    if ($interval->i < 10 || $interval->y > 0 || $interval->m > 0 || $interval->d > 0 || $interval->h > 0) {
                        $generate = true;
                    }
                }
            } else {
                $generate = true;
            }
            
            if ($generate) {
                $bancoOriginalAuth = new BancoOriginalAuth();
                $bancoOriginalAuth->grantCode();


                $success = Array(
                    "success" => true,
                    "message" => "Grant Code requested!"
                );
            } else {
                $success = Array(
                    "success" => true,
                    "message" => "Token valid!!"
                );
            }
            
            return response()->json($success, 200);
        } catch (\Exception $ex) {
            //echo $ex->getTraceAsString();
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    public function token(Request $request) {
        try {
            ob_start();

            echo $request->getMethod(). "\r\n\r\n";
            $all = $request->all();

            print_r($all);


            print_r($request->toArray());

            $conteudo = ob_get_contents();
            ob_end_clean();

            $arquivo = fopen("original_callbacks/" . time() . '.txt','w'); 
            if ($arquivo == false) {
                die('Não foi possível criar o arquivo.');
            }

            fwrite($arquivo, $conteudo);

            fclose($arquivo);
            
            $code = request("code");
            $bancoOriginalAuth = new BancoOriginalAuth();
            $bancoOriginalAuth->accessToken($code);
            
        } catch (\Exception $ex) {
            exit($ex->getMessage());
        }
        
        header("HTTP/1.1 200 Ok");
        exit("Welcome");
    }
}
