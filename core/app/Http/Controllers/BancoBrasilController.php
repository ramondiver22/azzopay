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

class BancoBrasilController extends Controller {
    
    public function index(Request $request) {
        ob_start();
        try {
            
            $pix = $request->all();
            echo json_encode($pix);
            
            foreach ($pix["pix"] as $pixData) {
                
                $txid = $pixData["txid"];

                $paymentInfo = (Object) Array(
                    "paymentMethod" => "pix",
                    "status" => 1,
                    "statusEnum" => "PAYMENT_CONFIRMED",
                    "statusDescription" => "Pagamento Confirmado",
                    "totalReceived" => number_format($pixData["valor"], 2, ".", ""),
                    "paymentId" => $pixData["txid"],
                    "json" => json_encode($pixData),
                    "transactionId" => $pixData["txid"],
                    "gateway" => "bancobrasil"
                );

                $invoice = Invoice::where("pix_transaction_id", $txid)->first();
                
                if ($invoice) {
                    $invoice->proccessPayment($paymentInfo, "pix", null);
                    //$userController = new UserController();
                    //$userController->payByBancoBrasilCallback($pixData);
                } else {

                    $deposit = Deposits::where("pix_transaction_id", $txid)->first();

                    if ($deposit) {
                        $deposit->proccessPayment($paymentInfo, "pix");
                    } else {

                        $donation = Donations::where("pix_transaction_id", $txid)->first();

                        if ($donation) {

                            $paymentLink = Paymentlink::where("id", $donation->donation_id)->first();
                            $paymentLink->proccessPayment($paymentInfo, "pix", null);

                        } else {

                            $paymentLink = Paymentlink::where("pix_transaction_id", $txid)->first();

                            if ($paymentLink) {

                                $paymentLink->proccessPayment($paymentInfo, "pix", null);

                            } else {

                                $order = Order::where("pix_transaction_id", $txid)->first();

                                if ($order) {
                                    $order->proccessPayment($paymentInfo, "pix", null);
                                }

                            }

                        }
                        
                    }

                }
                
                
            }
        } catch (\Exception $ex) {
            header("HTTP/1.1 500 Internal Server Error");
            exit($ex->getMessage());
        }
        
        $conteudo = ob_get_contents();
        ob_end_clean();
        
        $arquivo = fopen("bbcallbacks/" . time() . '.txt','w'); 
        if ($arquivo == false) {
            die('Não foi possível criar o arquivo.');
        }
        
        fwrite($arquivo, $conteudo);
        
        fclose($arquivo);
        
    }
    
}
