<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Invoice;
use App\Models\Audit;
use App\Models\History;
use App\Models\User;
use App\Models\Settings;
use App\Models\Charges;
use App\Models\Deposits;
use App\Models\UserTax;

class CieloPaymentController extends Controller {
    
    
    /**
     * 
     * @param String $invoiceRefId
     * @param \Cielo\API30\Ecommerce\Payment $cieloPayment
     * @return \App\Models\Transactions
     * @throws \Exception
     */
    public function creditCardPayment($invoiceRefId, $cieloPayment) {
        //$cieloPayment = new \Cielo\API30\Ecommerce\Payment();
        
        $paymentMethod = $cieloPayment->getType();
        $creditCardStatus = $cieloPayment->getStatus();
        $valorRecebido = number_format($cieloPayment->getCapturedAmount() / 100, 2, ".", "");
       

        $invoice = Invoice::where("ref_id", $invoiceRefId)->first();
        $status = $this->getTransactionStatus($creditCardStatus);
        
        if ($invoice != null) {

            if ($invoice->status == 1) {
                throw new \Exception("Invoice already paid.");
            }
            
            if ($invoice->status == 2) {
                throw new \Exception("Invoice already canceled.");
            }
            
            $set=Settings::first();

            $amount= $invoice->total-($invoice->total - $invoice->tax);
            $user= User::whereId($invoice->user_id)->first();
            $userTax = UserTax::getUserTax($invoice->user_id);
            $transaction = null;
            if ($paymentMethod != "Boleto") {
                
                $creditCardPaymentId = $cieloPayment->getPaymentId();
                $transaction = Transactions::where("creditcard_payment_id", $creditCardPaymentId)->first();
            }
            
            if ($transaction == null) {
                
                $xtoken='INV-'.str_random(6);
                $sav['ref_id']=$xtoken;
                $sav['type']=3;
                $sav['amount']=$valorRecebido;

                $sav['sender_id']=null;
                $sav['receiver_id']=$invoice->user_id;
                $sav['payment_link']=$invoice->id;
                $sav['payment_type']= ($paymentMethod == "Boleto" ? "boleto" : 'creditcard');
                $sav['gateway']= "cielo";
                $sav['invoice_id']= $invoice->id;
                
                $sav['ip_address']=user_ip();
                Transactions::create($sav);

                if ($paymentMethod == "Boleto") {
                    
                } else {
                    $creditCardBrand = $cieloPayment->getCreditCard()->getBrand();
                    $creditCardCallback = json_encode($cieloPayment);
                    $creditCardInstallments = $cieloPayment->getInstallments();
        
                    $sav['creditcard_payment_id']= $creditCardPaymentId;
                    $sav['creditcard_brand']= strtoupper($creditCardBrand);
                    $sav['creditcard_installments']= $creditCardInstallments;
                    $sav['creditcard_callback']= json_encode($creditCardCallback);
                    $sav['creditcard_status']= $status["status"];
                    $sav['creditcard_status_description']= $status["message"];
                }

                
            } else {
                $xtoken= $transaction->ref_id;
            }
           

            if (in_array($status["status"], Array("PAYMENT_CONFIRMED"))) {
                if ($valorRecebido >= $invoice->total) {
                    $invoice->status=1;
                    $invoice->charge= $invoice->total*$userTax->invoice_charge/100+($userTax->invoice_chargep);
                    $invoice->save();
                }
                
                $user->balance=$user->balance+$amount;
                $user->save();
                
                //Audit log
                $audit['user_id']=$user->id;
                $audit['trx']=str_random(16);
                $audit['log']='Payment for Invoice - '.$invoice->ref_id.' was successful';
                Audit::create($audit);
                
                //Charges
                $charge['user_id']=$user->id;
                $charge['ref_id']=$xtoken;
                $charge['amount']=$invoice->total*$userTax->invoice_charge/100+($userTax->invoice_chargep);
                $charge['log']='Charges for invoice #' .$invoice->ref_id;
                Charges::create($charge);

                $his['user_id']=$user->id;
                $his['amount']=$invoice->total-($invoice->total*$userTax->invoice_charge/100+($userTax->invoice_chargep));
                $his['ref']=$xtoken;
                $his['main']=0;
                $his['type']=1;
                History::create($his);
            
                //$cieloPayment = new \Cielo\API30\Ecommerce\Payment();
                //Change status to successful
                $change=Transactions::whereref_id($xtoken)->first();
                $change->status=1;
                $change->charge=$invoice->total*$userTax->invoice_charge/100+($userTax->invoice_chargep);
                $change->creditcard_authorization_code=$cieloPayment->getAuthorizationCode();
                $change->creditcard_transaction_id=$cieloPayment->getTid();
                $change->creditcard_proof_of_sale=$cieloPayment->getProofOfSale();

                $change->save(); 
                
                //Notify Users
                if($set->email_notify==1){
                    send_invoicereceipt($invoice->ref_id, ($paymentMethod == "Boleto" ? "boleto" : 'creditcard'), $xtoken);
                }
                
                return $change;
            } else if (in_array($status["status"], Array("VOIDED"))) { 
                //Change status to canceled
                $change=Transactions::whereref_id($xtoken)->first();
                $change->status=1;
                $change->save(); 
                
                return $change;
            } else {
                //Audit log
                $audit['user_id']=$user->id;
                $audit['trx']=str_random(16);
                $audit['log']='Payment status for Invoice - '.$invoice->ref_id.' changed: ' . $status["status"];
                Audit::create($audit);
                
                $change=Transactions::whereref_id($xtoken)->first();
                return $change;
            }
            

        }
    }
    
    public function depositPayment($depositRefId, $cieloPayment) {
        
        $paymentMethod = $cieloPayment->getType();
        $creditCardStatus = $cieloPayment->getStatus();
        $valorRecebido = number_format($cieloPayment->getCapturedAmount() / 100, 2, ".", "");
       

        $deposit = Deposits::where("trx", $depositRefId)->first();
        $status = $this->getTransactionStatus($creditCardStatus);
        
        if ($deposit != null) {

            if ($deposit->status == 1) {
                throw new \Exception("Deposit already paid.");
            }
            
            if ($deposit->status == 2) {
                throw new \Exception("Deposit already canceled.");
            }
            
            $set=Settings::first();
            $gate = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::CREDIT_CARD, $deposit->user_id);
                
            $charge= $deposit->charge;
            $amount= $deposit->amount - $deposit->charge;
            $user= User::whereId($deposit->user_id)->first();

            $dep = Deposits::where("trx", $depositRefId)->first();
            $dep->gateway = "cielo";
            if ($paymentMethod == "Boleto") {
                    
            } else {
                $creditCardPaymentId = $cieloPayment->getPaymentId();
                $creditCardBrand = $cieloPayment->getCreditCard()->getBrand();
                $creditCardCallback = json_encode($cieloPayment);
                $creditCardInstallments = $cieloPayment->getInstallments();
    
                
                $dep->creditcard_payment_id = $creditCardPaymentId;
                $dep->creditcard_brand = strtoupper($creditCardBrand);
                $dep->creditcard_installments = $creditCardInstallments;
                $dep->creditcard_callback = json_encode($creditCardCallback);
                $dep->creditcard_status = $status["status"];
                $dep->creditcard_status_description = $status["message"];
                $dep->save();
            }

            $xtoken = $deposit->trx;

            if (in_array($status["status"], Array("PAYMENT_CONFIRMED"))) {

                $user->balance=$user->balance+$amount;
                $user->save();
                
                //Audit log
                $audit['user_id']=$user->id;
                $audit['trx']=str_random(16);
                $audit['log']='Recarga de saldo - '.$deposit->ref_id.' feita com sucesso!';
                Audit::create($audit);
                //Charges
                $depositCharge['user_id']=$user->id;
                $depositCharge['ref_id']=$xtoken;
                $depositCharge['amount']=$charge;
                $depositCharge['log']='Taxas de recarga de saldo #' .$deposit->ref_id;
                Charges::create($depositCharge);

                $his['user_id']=$user->id;
                $his['amount']=$amount;
                $his['ref']=$xtoken;
                $his['main']=0;
                $his['type']=1;
                History::create($his);

                
                $cieloPayment = new \Cielo\API30\Ecommerce\Payment();
                //Change status to successful
                $change = Deposits::where("trx", $depositRefId)->first();
                $change->status=1;
                $change->creditcard_proof_of_sale=$cieloPayment->getAuthorizationCode();
                $change->creditcard_transaction_id=$cieloPayment->getTid();
                $change->creditcard_proof_of_sale=$cieloPayment->getProofOfSale();
                $change->save(); 
                
                //Notify Users
                //if($set->email_notify==1){
                    //send_invoicereceipt($deposit->ref_id, ($paymentMethod == "Boleto" ? "boleto" : 'creditcard'), $xtoken);
                //}
                
                return $change;
            } else if (in_array($status["status"], Array("VOIDED"))) { 
                //Change status to canceled
                $change=Deposits::where("trx", $depositRefId)->first();
                $change->status=1;
                $change->save(); 
                
                return $change;
            } else {
                //Audit log
                $audit['user_id']=$user->id;
                $audit['trx']=str_random(16);
                $audit['log']='Payment status for Deposit - '.$deposit->ref_id.' changed: ' . $status["status"];
                Audit::create($audit);
                
                $change=Deposits::where("trx", $depositRefId)->first();
                return $change;
            }
            

        }
    }
    
    private function getTransactionStatus($statusCode) {
        switch ($statusCode) {
            case 0: return Array("status" => "NOT_FINISHED", "message" => "Aguardando atualização de status.");
            case 1: return Array("status" => "AUTHORIZED", "message" => "Pagamento apto a ser capturado ou definido como pago.");
            case 2: return Array("status" => "PAYMENT_CONFIRMED", "message" => "Pagamento confirmado e finalizado.");
            case 3: return Array("status" => "DENIED", "message" => "Pagamento negado por Autorizador.");
            case 10: return Array("status" => "VOIDED", "message" => "Pagamento cancelado.");
            case 11: return Array("status" => "REFUNDED", "message" => "Pagamento cancelado após 23h59 do dia de autorização.");
            case 12: return Array("status" => "PENDING", "message" => "Aguardandoretorno da instituição financeira.");
            case 13: return Array("status" => "ABORTED", "message" => "Pagamento cancelado por falha no processamento ou por ação do Antifraude.");
            case 20: return Array("status" => "SCHEDULED", "message" => "Recorrência agendada.");
            default: return Array("status" => "UNKNOW", "message" => "Desconhecido");
        }
    }
    
    
    
   
}
