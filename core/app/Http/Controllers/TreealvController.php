<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Paymentlink;
use App\Models\Donations;
use App\Models\Deposits;
use App\Models\Order;
use App\Models\Withdraw;

class TreealvController extends Controller {
    
    public function index(Request $request) {
        ob_start();

        if (!file_exists("./treealcallbacks")) {
            mkdir("./treealcallbacks");
        }

        if (!file_exists("./treealcallbacks/cashin/")) {
            mkdir("./treealcallbacks/cashin/");
        }

        if (!file_exists("./treealcallbacks/cashout/")) {
            mkdir("./treealcallbacks/cashout/");
        }

        $logFile = "./treealcallbacks/cashin/" . time() . '.txt';
        try {
            
            $pix = $request->all();
            echo json_encode($pix);
            
            if ($pix["webhookType"] == "CashIn") {
                
                $txid = $pix["data"]["receiverConciliationId"];
                if ($pix["data"]["status"] == "SUCESSO") {
                    $paymentInfo = (Object) Array(
                        "paymentMethod" => "pix",
                        "status" => 1,
                        "statusEnum" => "PAYMENT_CONFIRMED",
                        "statusDescription" => "Pagamento Confirmado",
                        "totalReceived" => number_format($pix["amount"], 2, ".", ""),
                        "paymentId" => $pix["data"]["endToEndId"],
                        "json" => json_encode($pix),
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

            } else if ($pix["webhookType"] == "CashOut") {
                $logFile = "./treealcallbacks/cashout/" . time() . '.txt';

                $txid = $pix["data"]["pixTransferKey"];

                $withdraw = Withdraw::where("pix_txid", $txid)->first();
                if ($withdraw) {

                    if (isset($pix["data"]["error"])) {
                        $withdraw->cancelWithdraw($pix["data"]["error"]["Detail"]);
                    } else if ($pix["data"]["pixTransferStatus"] == "SUCESSO") {
                        if ($withdraw->status == 0) {
                            $withdraw->confirmPayment($pix["data"]["endToEndId"]);
                        }
                    }
                    
                }

            }

        } catch (\Exception $ex) {
            header("HTTP/1.1 500 Internal Server Error");

            echo $ex->getMessage();
            echo $ex->getTraceAsString();
        }
        
        $conteudo = ob_get_contents();
        ob_end_clean();
        
        $arquivo = fopen($logFile,'w'); 
        if ($arquivo == false) {
            die('Não foi possível criar o arquivo.');
        }
        
        fwrite($arquivo, $conteudo);
        
        fclose($arquivo);
        
    }
    
}
