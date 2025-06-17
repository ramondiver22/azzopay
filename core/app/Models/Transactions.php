<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model {
    protected $table = "transactions";
    protected $guarded = [];

    public function ddlink()
    {
        return $this->belongsTo('App\Models\Paymentlink', 'payment_link');
    }       
    public function inplan()
    {
        return $this->belongsTo('App\Models\Invoice', 'payment_link');
    }
    public function sender()
    {
        return $this->belongsTo('App\Models\User','sender_id');
    }    
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }
    
    public static function getTransactionStatus($statusCode) {
        switch ($statusCode) {
            case 0: return "PENDING";
            case 1: return "PAID";
            case 2: return "CANCELED";
            case 3: return "REFUNDED";
            default: 
                return "";
        }
    }


    public static function proccessInvoicePayment($invoice, $paymentInfo, $paymentMethod, $sender = null) {
        return self::createTransaction(3, $invoice->user_id, $paymentInfo, $paymentMethod, $sender, $invoice, null);
    }

    public static function proccessSingleChargePayment($userId, $singleCharge, $paymentInfo, $paymentMethod, $sender = null) {
        return self::createTransaction(1, $userId, $paymentInfo, $paymentMethod, $sender, null, null, $singleCharge);
    }

    public static function proccessDonationPayment($userId, $donation, $paymentInfo, $paymentMethod, $sender = null) {
        return self::createTransaction(2, $userId, $paymentInfo, $paymentMethod, $sender, null, $donation);
    }


    private static function createTransaction($type, $userId, $paymentInfo, $paymentMethod, $sender = null, $invoice = null, $donation = null, $singleCharge = null) {
        $transaction = null;
        $set = Settings::first();

        if (strtolower($paymentMethod) == "creditcard") {
            //$transaction = Transactions::where("creditcard_payment_id", $paymentInfo->paymentId)->first();
        } 
        $userTax = UserTax::getUserTax($userId);
        $charge = 0;
        if ($type == 1) {
            $charge = number_format(($paymentInfo->totalReceived * $userTax->single_charge / 100+($userTax->single_chargep)), 2, ".", "");
        } elseif ($type == 2) {
            $charge = number_format(($paymentInfo->totalReceived * $userTax->donation_charge / 100+($userTax->donation_chargep)), 2, ".", "");
        } else if ($type == 3){
            $charge = number_format(($paymentInfo->totalReceived * $userTax->invoice_charge / 100+($userTax->invoice_chargep)), 2, ".", "");
        }

        if ($transaction == null) {
            $xtoken='';
            if ($invoice != null) {
                $xtoken='INV-'.str_random(6);
            } else if ($donation != null) {
                $xtoken='DON-'.str_random(6);
            } else {
                $xtoken=str_random(6);
            }
            
            $sav['ref_id']=$xtoken;
            $sav['status']=1;
            $sav['type']=$type;
            $sav['amount'] = ($paymentInfo->totalReceived - $charge);
            $sav['charge'] = $charge;
            $sav['sender_id']= ($sender != null ?$sender->id : null);
            $sav['receiver_id']= $userId;
            $sav['payment_link']= ($invoice != null ? $invoice->id : ($donation != null ? $donation->donation_id : ($singleCharge != null ? $singleCharge->id : 0)));
            $sav['payment_type']= strtolower($paymentMethod);
            $sav['gateway']= (isset($paymentInfo->gateway) ? $paymentInfo->gateway : null);
            $sav['invoice_id']= ($invoice != null ? $invoice->id : null);
            $sav['donation_id']= ($donation != null ? $donation->id : null);
            $sav['ip_address']=user_ip();

            $transaction = Transactions::create($sav);
            
        } else {
            $transaction->amount = ($paymentInfo->totalReceived - $charge);
            $transaction->charge = $charge;
        }

        $transaction->creditcard_payment_id = (isset($paymentInfo->paymentId) ? $paymentInfo->paymentId : null);
        $transaction->creditcard_brand = (isset($paymentInfo->brand) ? strtoupper($paymentInfo->brand) : null);
        $transaction->creditcard_installments = (isset($paymentInfo->installments) ? $paymentInfo->installments : null);
        $transaction->creditcard_callback = (isset($paymentInfo->json) ? $paymentInfo->json : null);
        $transaction->creditcard_status = (isset($paymentInfo->statusEnum) ? $paymentInfo->statusEnum : null);
        $transaction->creditcard_status_description = (isset($paymentInfo->statusDescription) ? $paymentInfo->statusDescription : null);
        $transaction->creditcard_authorization_code = (isset($paymentInfo->authorizationCode) ? $paymentInfo->authorizationCode : null);
        $transaction->creditcard_transaction_id = (isset($paymentInfo->transactionId) ? $paymentInfo->transactionId : null);
        $transaction->creditcard_proof_of_sale = (isset($paymentInfo->proofOfSale) ? $paymentInfo->proofOfSale : null);

        $transaction->save();
        return $transaction;
    }

}
