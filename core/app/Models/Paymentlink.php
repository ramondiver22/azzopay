<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentlink extends Model {
    protected $table = "payment_link";
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    } 


    public static function getLinkStatus($statusCode) {
        switch ($statusCode) {
            case 0: return "PENDING";
            case 1: return "PAID";
            case 2: return "CANCELED";
            case 3: return "REFUNDED";
            default: 
                return "";
        }
    }


    public function updatePixInfo($txId, $qrcode, $copy, $totalValue = 0, $anonymous = 1, $donnorId = null) {
        
        if ($this->type == 1) {
            $this->pix_transaction_id = $txId;
            $this->pix_qrcode = $qrcode;
            $this->pix_copy_past = $copy;
    
            self::where("id", $this->id)
            ->update(Array(
                "pix_transaction_id" => $txId,
                "pix_qrcode" => $qrcode,
                "pix_copy_past" => $copy
            ));
            return $this;
        } else if ($this->type == 2){
            $donation = Donations::createDonation($totalValue, $donnorId, $this->id, $anonymous);
            $donation->updatePixInfo($txId, $qrcode, $copy);
            return $donation;
        }
        

    }


    public function updateBoletoInfo($paymentId, $boletoURL, $barcode, $digitableLine, $totalValue = 0, $anonymous = 1, $donnorId = null) {
        
        if ($this->type == 1) {
            $this->boleto_transaction_id = $paymentId;
            $this->boleto_url = $boletoURL;
            $this->boleto_barcode = $barcode;
            $this->boleto_digitable_line = $digitableLine;


            self::where("id", $this->id)
                ->update(Array(
                    "boleto_transaction_id" => $paymentId,
                    "boleto_url" => $boletoURL,
                    "boleto_barcode" => $barcode,
                    "boleto_digitable_line" => $digitableLine
                ));

            return $this;
        } else if ($this->type == 2){
            $donation = Donations::createDonation($totalValue, $donnorId, $this->id, $anonymous);
            $donation->updateBoletoInfo($paymentId, $boletoURL, $barcode, $digitableLine);

            return $donation;
        }
    }


    

    public function proccessPayment($paymentInfo, $paymentMethod, $userClient = null, $anonymous = 0) {

        
        if ($this->status == 1) {
            throw new \Exception("Payment Link already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Payment Link already canceled.");
        }

        $set = Settings::first();
        $user= User::whereId($this->user_id)->first();

        $userTax = UserTax::getUserTax($user->id);

        if ($paymentInfo->status == 1) {

            if ($this->type == 1) {
                if (strtolower($paymentMethod) == "creditcard") {
                    $this->total = $paymentInfo->totalReceived;
                    $this->charge = number_format(($paymentInfo->totalReceived - $paymentInfo->originalAmount), 2, ".", "");
                    $this->amount = $paymentInfo->originalAmount;
                } else {
                    $this->total = $paymentInfo->totalReceived;
                    $this->charge = number_format((($this->total * $userTax->single_charge / 100) + $userTax->single_chargep), 2, ".", "");
                    $this->amount = ($this->total - $this->charge);
                }
                
                
                $this->status = 1;
                $this->received_total += $paymentInfo->totalReceived;
                $this->payment_type = strtolower($paymentMethod);


                $this->creditcard_payment_id = (isset($paymentInfo->paymentId) ? $paymentInfo->paymentId : null);
                $this->creditcard_brand = (isset($paymentInfo->brand) ? strtoupper($paymentInfo->brand) : null);
                $this->creditcard_installments = (isset($paymentInfo->installments) ? $paymentInfo->installments : null);
                $this->creditcard_callback = (isset($paymentInfo->json) ? $paymentInfo->json : null);
                $this->creditcard_status = (isset($paymentInfo->statusEnum) ? $paymentInfo->statusEnum : null);
                $this->creditcard_status_description = (isset($paymentInfo->statusDescription) ? $paymentInfo->statusDescription : null);
                $this->creditcard_authorization_code = (isset($paymentInfo->authorizationCode) ? $paymentInfo->authorizationCode : null);
                $this->creditcard_transaction_id = (isset($paymentInfo->transactionId) ? $paymentInfo->transactionId : null);
                $this->creditcard_proof_of_sale = (isset($paymentInfo->proofOfSale) ? $paymentInfo->proofOfSale : null);

                $this->client_name = (isset($paymentInfo->client_name) ? $paymentInfo->client_name : null);
                $this->client_document = (isset($paymentInfo->client_document) ? $paymentInfo->client_document : null);

                $this->save();

                if (strtolower($paymentInfo->paymentMethod) == "creditcard") { 
                    PendingBalance::saveBalance($user->id, $this->amount, $this->charge, PendingBalance::SINGLE_CHARGE, $this->ref_id, "Link de Pagamento {$this->id} ");
                } else {
                    $user->updateBalance($user, $this->amount, "credit");
                    $chargeDescription = 'Single Charge Payment #'. $this->ref_id;
                    Charges::registerCharge($user->id, $this->ref_id, $this->charge, $chargeDescription);

                    History::registerHistory($user->id, $this->amount, $this->ref_id, 0, 1);

                }
                
                $transaction = Transactions::proccessSingleChargePayment($user->id, $this, $paymentInfo, $paymentMethod, $userClient);
                
                $logDescription = 'Received payment for Payment Link - '.$this->ref_id.' was successful';
                Audit::registerLog($user->id, $this->ref_id, $logDescription);

                
                //Notify users
                if($set->email_notify==1){
                    send_paymentlinkreceipt($this->ref_id, strtolower($paymentMethod), $this->ref_id);
                } 


                if (!empty($user->callback_url)) {
                    $body = Array(
                        "entity" => "PAYMENT_LINK",
                        "reference" => $this->ref_id,
                        "receivedValue" => $paymentInfo->totalReceived,
                        "receivedTotal" => $this->received_total
                    );
                    Callback::createCallback("PAYMENT_LINK", $this->ref_id, $this->id, $body, $user->callback_url);
                }
    
            } else if ($this->type == 2) {
                $this->received_total += $paymentInfo->totalReceived;
                $this->save();

                $donation = Donations::getOpenByPaymentId(strtolower($paymentMethod), $paymentInfo->paymentId);

                if (!$donation) {
                    $donation = Donations::createDonation($paymentInfo->totalReceived, $this->user->id, $this->id, $anonymous);
                }

                $donation->proccessPayment($paymentInfo, $paymentMethod, $user, $userClient);

            }

        }
    }



    public function proccessPaymentWithAccountBalance($userClient, $totalPaid, $anonymous) {

        if ($this->status == 1) {
            throw new \Exception("Payment Link already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Payment Link already canceled.");
        }

        if (!($totalPaid > 0)) {
            throw new \Exception("O valor do pagamento precisa ser maior que zero");
        }
        
        $set = Settings::first();
        $user= User::whereId($this->user_id)->first();
        $userTax = UserTax::getUserTax($user->id);
        if ($this->type == 1) {
            if ($userClient->id == $this->user_id) {
                throw new \Exception("Você não pode pagar um link gerado em sua própria conta.");
            }
            $this->total = $totalPaid;
            if($this->total > $user->balance) {
                throw new \Exception("Saldo insuficiente.");
            }
            $this->received_total += $totalPaid;
            $this->charge = number_format((($this->total * $userTax->single_charge / 100) + $userTax->single_chargep), 2, ".", "");
            $this->amount = ($this->total - $this->charge);
            $this->payment_type = "account";
            $this->status=1;
            
            $this->save();

            $user->updateBalance($user, $this->amount, "credit");
            $userClient->updateBalance($user, $this->total, "debit");


            $paymentInfo = (Object) Array(
                "totalReceived" => $totalPaid,
                "gateway" => "account"
            );
            $transaction = Transactions::proccessSingleChargePayment($user->id, $this, $paymentInfo, $paymentMethod, $userClient);
                
            $logDescription = 'Received payment for Payment Link - '.$this->ref_id.' was successful';
            Audit::registerLog($user->id, $this->ref_id, $logDescription);

            $chargeDescription = 'Single Charge Payment #'. $this->ref_id;
            Charges::registerCharge($user->id, $this->ref_id, $this->charge, $chargeDescription);

            History::registerHistory($user->id, $this->amount, $this->ref_id, 0, 1);
            History::registerHistory($userClient->id, $this->total, $this->ref_id, 0, 2);


            if (!empty($user->callback_url)) {
                $body = Array(
                    "entity" => "PAYMENT_LINK",
                    "reference" => $this->ref_id,
                    "receivedValue" => $paymentInfo->totalReceived,
                    "receivedTotal" => $this->received_total
                );
                Callback::createCallback("PAYMENT_LINK", $this->ref_id, $this->id, $body, $user->callback_url);
            }


        } else if ($this->type == 2) {
            $this->received_total += $totalPaid;
            $this->save();

            $donation = Donations::createDonation($totalPaid, $this->user->id, $this->id, $anonymous);
            $donation->proccessPaymentWithAccountBalance($user, $userClient, $totalPaid);
        }
        
    }

}
