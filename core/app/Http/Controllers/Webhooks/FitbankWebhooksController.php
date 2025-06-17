<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Paymentlink;
use App\Models\Donations;
use App\Models\Deposits;
use App\Models\Order;
use App\Models\Withdraw;
use App\Models\Callback;



class FitbankWebhooksController extends Controller {
    
    private $authentication = "UFVCLTRpaktLdld5dDBWUnEzbFYzSWdwRVdyWVlJMXF1ckRDOlNFQy1DdDFNU3J5RWFOZUhvRWVZNW9iV3NIcFRycW1mT2RIeQ==";
    
    public function index(Request $request) {
        ob_start();

        if (!file_exists("./fitbankcallbacks")) {
            mkdir("./fitbankcallbacks");
        }

        if (!file_exists("./fitbankcallbacks/cashin/")) {
            mkdir("./fitbankcallbacks/cashin/");
        }

        if (!file_exists("./fitbankcallbacks/cashout/")) {
            mkdir("./fitbankcallbacks/cashout/");
        }

        $logFile = "./fitbankcallbacks/cashin/" . time() . '.txt';
        try {

            print_r($_SERVER);

            echo PHP_EOL . PHP_EOL . PHP_EOL;

            $authorization = $this->getHeaderAuthorization($request, "Basic"); 

            if ($authorization != $this->authentication) {
                throw new \Exception("Autenticação Inválida", 401);
            }

            $callback = $request->all();
            echo  json_encode($callback) . PHP_EOL . PHP_EOL;

            if (isset($callback["Method"]) && $callback["Method"] == "PixIn") {
                
                $txid = $callback["QRCodeInfos"]["DocumentNumber"];

                if ($callback["Status"] == "Paid") {
                    $paymentInfo = (Object) Array(
                        "paymentMethod" => "pix",
                        "status" => 1,
                        "statusEnum" => "PAYMENT_CONFIRMED",
                        "statusDescription" => "Pagamento Confirmado",
                        "totalReceived" => number_format($callback["Value"], 2, ".", ""),
                        "paymentId" => $callback["EntryId"],
                        "json" => json_encode($callback),
                        "transactionId" => $txid,
                        "gateway" => "treeal"
                    );

                    $invoice = Invoice::where("pix_transaction_id", $txid)->first();
                
                    if ($invoice) {
                        if ($invoice->status == 0) {
                            $invoice->proccessPayment($paymentInfo, "pix", null);
                        }
                        
                    } else {

                        $deposit = Deposits::where("pix_transaction_id", $txid)->first();

                        if ($deposit) {
                            if ($deposit->status == 0) {
                                $deposit->proccessPayment($paymentInfo, "pix");
                            }
                        } else {

                            $donation = Donations::where("pix_transaction_id", $txid)->first();

                            if ($donation) {
                                if ($donation->status == 0) {
                                    $paymentLink = Paymentlink::where("id", $donation->donation_id)->first();
                                    $paymentLink->proccessPayment($paymentInfo, "pix", null);
                                }

                            } else {

                                $paymentLink = Paymentlink::where("pix_transaction_id", $txid)->first();

                                if ($paymentLink) {
                                    if ($paymentLink->status == 0) {
                                        $paymentLink->proccessPayment($paymentInfo, "pix", null);
                                    }

                                } else {

                                    $order = Order::where("pix_transaction_id", $txid)->first();

                                    if ($order) {
                                        if ($order->status == 0) {
                                            $order->proccessPayment($paymentInfo, "pix", null);
                                        }
                                    }

                                }

                            }
                            
                        }

                    }
                }

            } else if (isset($callback["Method"]) && $callback["Method"] == "PixOut") {
                
                $logFile = "./fitbankcallbacks/cashout/" . time() . '.txt';
                $status = $callback["Status"];
                $txid = $callback["DocumentNumber"];

                $withdraw = Withdraw::where("pix_txid", $txid)->first();

                if ($withdraw) {
                    if ($withdraw->status == 0) {
                        if ($status == "Paid" || $status == "Settled") {
                            $withdraw->confirmPayment($callback["EndToEndId"]);

                            echo PHP_EOL . PHP_EOL . "Saque Confirmado: {$withdraw->id} " . PHP_EOL . PHP_EOL;
                        } else if ($status == "Cancel"){
                            $withdraw->cancelWithdraw($callback["ErrorDescription"]);
                        }
                    } else if ($withdraw->status == 1) {
                        echo PHP_EOL . PHP_EOL . "Saque Já Confirmado: {$withdraw->id} " . PHP_EOL . PHP_EOL;
                    } else if ($withdraw->status == 2) {
                        echo PHP_EOL . PHP_EOL . "Saque Já Cancelado: {$withdraw->id} " . PHP_EOL . PHP_EOL;
                    }
                } else {
                    echo PHP_EOL . PHP_EOL . "Saque Não Encontrado " . PHP_EOL . PHP_EOL;
                }
                
                   

            }
            

            $json["Success"] = true;
            $json["Message"] = "Operação realizada com sucesso";
            
            
        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
            $json["Success"] = false;
            $json["Message"] = $ex->getMessage();
        }
        $conteudo = ob_get_contents();
        ob_end_clean();
        
        
        $arquivo = fopen($logFile,'w'); 
        if ($arquivo == false) {
            die('Não foi possível criar o arquivo.');
        }
        
        fwrite($arquivo, $conteudo);
        
        fclose($arquivo);
        

        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        exit(json_encode($json));
    }
    


    private function getHeaderAuthorization(Request $request, $type) {

        $authorization = trim(str_replace(Array($type, strtoupper($type)), "", $request->header('Authorization')));
        
        if (empty($authorization)) {
            $authorization = trim(str_replace(Array($type, strtoupper($type)), "", $request->header('REDIRECT_HTTP_AUTHORIZATION')));
            if (empty($authorization)) {
                $authorization = trim(str_replace(Array($type, strtoupper($type)), "", $request->header('HTTP_AUTHORIZATION')));
                if (empty($authorization)) {
                    throw new \Exception("Autenticação não informada.", 401);
                }
            }
        }
        
        return $authorization;
    }


}