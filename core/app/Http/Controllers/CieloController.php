<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CieloController extends Controller {
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        ob_start();
        try {
            
            $info = $request->all();
            
            echo json_encode($info);
            
            
            $paymentId = $info["PaymentId"];
            $changType =  $info["ChangeType"];
            
            if ($changType == 1) {
                
                $cieloLib = new \App\Lib\Cielo_lib();
                
                $sale = $cieloLib->consultByPaymentId($paymentId);
                

                //exit(print_r($sale));
                if ($sale) {
                    $paymentData = $sale->getPayment();
                    $refId = $sale->getMerchantOrderId();
                    //$paymentData = new \Cielo\API30\Ecommerce\Payment();
                    $paymentData->setCapturedAmount($paymentData->getAmount());
                    $paymentData->setStatus(2);

                    // O token gerado pode ser armazenado em banco de dados para vendar futuras
                    //$token = $sale->getPayment()->getCreditCard()->getCardToken();
                    $statusTransaction = $sale->getPayment()->getStatus();

                    if ($statusTransaction != 1 && $statusTransaction != 2) {
                        $returnCode = $sale->getPayment()->getReturnCode();
                        $returnMessage = $sale->getPayment()->getReturnMessage();
                        throw new \Exception("{$returnCode} - {$returnMessage}", 400);
                    }
                    

                    $paymentId = $sale->getPayment()->getPaymentId();
                    
                    // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
                    $paymentCaptured = (new CieloEcommerce($merchant, $environment))->captureSale($paymentId, number_format($transactionTotal, 2, "", ""), 0);
                    
                    $paymentCaptured->setPaymentId($paymentId);
                    $paymentCaptured->setCreditCard($payment->getCreditCard());
                    $paymentCaptured->setInstallments($cardData["installments"]);
                    $paymentCaptured->setCapturedAmount(number_format($transactionTotal, 2, "", ""));
                    
                    /*
                    $status = $this->getTransactionStatus($paymentCaptured->getStatus());

                    return (Object) Array(
                        "paymentMethod" => $paymentCaptured->getType(),
                        "status" => $this->toTransactionStatus($paymentCaptured->getStatus()),
                        "statusEnum" => $status["status"],
                        "statusDescription" => $status["message"],
                        "totalReceived" => number_format($paymentCaptured->getCapturedAmount() / 100, 2, ".", ""),
                        "paymentId" => $paymentCaptured->getPaymentId(),
                        "brand" => $paymentCaptured->getCreditCard()->getBrand(),
                        "json" => json_encode($paymentCaptured),
                        "installments" => $paymentCaptured->getInstallments(),
                        "authorizationCode" => $paymentCaptured->getAuthorizationCode(),
                        "transactionId" => $paymentCaptured->getTid(),
                        "proofOfSale" => $paymentCaptured->getProofOfSale(),
                        "gateway" => "cielo"
                    );
                    */
                    //$cieloPayment = new CieloPaymentController();



                    //$cieloPayment->creditCardPayment($refId, $paymentData);
                }
                
                
            }
            
            
        } catch (\Exception $ex) {
            header("HTTP/1.1 500 Internal Server Error");
            exit($ex->getMessage());
        }
        
        $conteudo = ob_get_contents();
        ob_end_clean();
        
        $arquivo = fopen("cielo_callbacks/" . time() . '.txt','w'); 
        if ($arquivo == false) {
            die('Não foi possível criar o arquivo.');
        }
        
        fwrite($arquivo, $conteudo);
        
        fclose($arquivo);
        header("HTTP/1.1 200 Ok");
    }
    
}
