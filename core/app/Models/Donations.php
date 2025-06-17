<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model {
    protected $table = "donations";
    protected $guarded = [];

    public function ddlink() {
        return $this->belongsTo('App\Models\Paymentlink', 'payment_link');
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    } 

    public static function getOpenByPaymentId($paymentMethod, $transactionId) {
        if ($paymentMethod == "boleto") {
            return self::where("boleto_transaction_id", $transactionId)->where("status", 0)->first();
        } else if ($paymentMethod == "pix") {
            return self::where("pix_transaction_id", $transactionId)->where("status", 0)->first();
        }
    }

    public function createDonation($amount, $userId, $paymentLinkId, $anonymous = 0) {
        $xtoken='DN-'.str_random(6);
        $data = Array(
            "amount" => $amount,
            "user_id" => $userId,
            "status" => 0,
            "anonymous" => $anonymous,
            "ref_id" => $xtoken,
            "donation_id" => $paymentLinkId,
        );

        return self::create($data);
    }

    public function updatePixInfo($txId, $qrcode, $copy) {
        
        $this->pix_transaction_id = $txId;
        $this->pix_qrcode = $qrcode;
        $this->pix_copy_past = $copy;

        self::where("id", $this->id)
        ->update(Array(
            "pix_transaction_id" => $txId,
            "pix_qrcode" => $qrcode,
            "pix_copy_past" => $copy
        ));

        return self;
    }


    public function updateBoletoInfo($paymentId, $boletoURL, $barcode, $digitableLine) {
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

            return self;
    }


    

    public function proccessPayment($paymentInfo, $paymentMethod, $userReceiver, $userClient = null) {

        
        if ($this->status == 1) {
            throw new \Exception("Donation already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Donation already canceled.");
        }

        $set = Settings::first();

        if ($paymentInfo->status == 1) {

            $charge = 0;
            $totalAmount = 0;
            if (strtolower($paymentInfo->paymentMethod) == "creditcard") { 
                $charge = ($paymentInfo->totalReceived - $paymentInfo->originalAmount);
                $totalAmount = $paymentInfo->originalAmount;
            } else {
                $charge = $transaction->charge;
                $totalAmount = $transaction->amount;
            }

            $this->user_id = $userReceiver->id;
            $this->amount = $totalAmount;
            $this->status = 1;
            $this->payment_type = strtolower($paymentMethod);

            $this->save();
            $transaction = Transactions::proccessDonationPayment($userReceiver->id, $this, $paymentInfo, $paymentMethod, $userClient);


            if (strtolower($paymentInfo->paymentMethod) == "creditcard") { 
                PendingBalance::saveBalance($userReceiver->id,  $totalAmount, $charge, PendingBalance::DONATION, $this->ref_id, "Doação {$this->id} ");
            } else {
                $userReceiver->updateBalance($userReceiver, $totalAmount, "credit");
                $chargeDescription = 'Received Donation for Payment Link #'. $this->ref_id;
                Charges::registerCharge($userReceiver->id, $this->ref_id, $charge, $chargeDescription);

                History::registerHistory($userReceiver->id, $totalAmount, $this->ref_id, 0, 1);
            }

            $logDescription = 'Received Donation for Payment Link - '.$this->ref_id.' was successful';
            Audit::registerLog($userReceiver->id, $this->ref_id, $logDescription);

            $link = Paymentlink::where("id", $this->donation_id)->first();
            //Notify users
            if($set->email_notify==1){
                send_paymentlinkreceipt($link->ref_id, strtolower($paymentMethod), $transaction->ref_id);
            } 


            if (!empty($userReceiver->callback_url)) {
                $body = Array(
                    "entity" => "DONATION",
                    "reference" => $this->ref_id,
                    "receivedValue" => $this->amount,
                    "receivedTotal" => $this->amount
                );
                Callback::createCallback("DONATION", $this->ref_id, $this->id, $body, $userReceiver->callback_url);
            }
        }
    }



    public function proccessPaymentWithAccountBalance($userReceiver, $userClient, $totalPaid) {

        if ($this->status == 1) {
            throw new \Exception("Payment Link already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Payment Link already canceled.");
        }

        if (!($totalPaid > 0)) {
            throw new \Exception("O valor do pagamento precisa ser maior que zero");
        }
        
        if ($userClient->id == $userReceiver->id) {
            throw new \Exception("Você não pode pagar um link gerado em sua própria conta.");
        } 
        
        $set = Settings::first();
        $user= User::whereId($userClient->id)->first();

        if($totalPaid > $user->balance) {
            throw new \Exception("Saldo insuficiente.");
        }

        $paymentInfo = (Object) Array(
            "totalReceived" => $totalPaid,
            "gateway" => "account"
        );

        $transaction = Transactions::proccessDonationPayment($userReceiver->id, $this, $paymentInfo, "account", $userClient);

        $this->amount = $totalPaid;
        $this->payment_type = "account";
        $this->status=1;
        
        $this->save();

        $userReceiver->updateBalance($userReceiver, $transaction->amount, "credit");
        $userClient->updateBalance($userClient, $totalPaid, "debit");

        $logDescription = 'Received Donation for Payment Link - '.$this->ref_id.' was successful';
        Audit::registerLog($userReceiver->id, $this->ref_id, $logDescription);

        $chargeDescription = 'Received Donation for Payment Link #'. $this->ref_id;
        Charges::registerCharge($userReceiver->id, $this->ref_id, $transaction->charge, $chargeDescription);

        History::registerHistory($userReceiver->id, $transaction->amount, $this->ref_id, 0, 1);
        History::registerHistory($userClient->id, $totalPaid, $this->ref_id, 0, 2);

        $link = Paymentlink::where("id", $this->donation_id)->first();
        //Notify users
        if($set->email_notify==1){
            send_paymentlinkreceipt($link->ref_id, strtolower($paymentMethod), $transaction->ref_id);
        } 


        if (!empty($userReceiver->callback_url)) {
            $body = Array(
                "entity" => "DONATION",
                "reference" => $this->ref_id,
                "receivedValue" => $this->amount,
                "receivedTotal" => $this->amount
            );
            Callback::createCallback("DONATION", $this->ref_id, $this->id, $body, $userReceiver->callback_url);
        }
    }


}
