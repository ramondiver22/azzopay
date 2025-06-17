<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposits extends Model {
    protected $table = "deposits";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function gateway()
    {
        return $this->belongsTo('App\Models\Gateway','gateway_id');
    }



    public static function getDepositStatus($statusCode) {
        switch ($statusCode) {
            case 0: return "PENDING";
            case 1: return "PAID";
            case 2: return "CANCELED";
            case 3: return "REFUNDED";
            default: 
                return "";
        }
    }

    public static function createDeposit($userId, $paymentType, $gatewayId, $amount, $charge, $currency = null) {
        $token=str_random(16);
        $secret=str_random(8);
        
        $depo = Array(
            'user_id' => $userId,
            'payment_type' => strtolower($paymentType),
            'gateway_id' => $gatewayId,
            'amount' => $amount,
            'charge' => $charge,
            'trx' => $token,
            'secret' => $secret,
            'status' => 0,
            'currency_id' => $currency->id,
            'currency_name' => $currency->name,
            'rate' => $currency->brl_quote
        );

        $deposit = self::create($depo);

        $description = 'Created Funding Request of '. $deposit->amount . $currency->name.' via '.$deposit->payment_type;
        Audit::registerLog($deposit->user_id, $deposit->trx, $description);

        return $deposit;
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
    }



    public function proccessPayment($paymentInfo, $paymentMethod) {

        if ($this->status == 1) {
            throw new \Exception("Fund already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Fund already canceled.");
        }

        $set = Settings::first();
        $user= User::whereId($this->user_id)->first();

        if ($paymentInfo->status == 1) {


            $totalAmount = $paymentInfo->totalReceived;
            //$charge = ($totalAmount * ($gateway->charge / 100));
            //$amount = ($totalAmount - $charge);

            $amount = ($totalAmount - $this->charge);
            $charge = $this->charge;

            $this->status=1;

            //$this->charge = $charge;
            //$this->amount = $totalAmount;
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

            
            $user->updateBalance($user, $amount, "credit");

            $chargeDescription = 'Taxas de recarga de saldo #' .$this->trx;
            Charges::registerCharge($user->id, $this->trx, $charge, $chargeDescription);

            History::registerHistory($user->id, $amount, $this->trx, 0, 1);
     

            $logDescription = 'Recarga de saldo - '.$this->trx.' feita com sucesso!';
            Audit::registerLog($user->id, $this->trx, $logDescription);


            if (!empty($user->callback_url)) {
                $body = Array(
                    "entity" => "FUND",
                    "reference" => $this->trx,
                    "receivedValue" => $paymentInfo->totalReceived,
                    "receivedTotal" => $paymentInfo->totalReceived
                );
                Callback::createCallback("FUND", $this->trx, $this->id, $body, $user->callback_url);
            }
        } 
    }
}
