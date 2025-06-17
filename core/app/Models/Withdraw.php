<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Pix\PixGateway;
use App\Lib\BillingUtils;
use Illuminate\Support\Facades\DB;

class Withdraw extends Model {
    protected $table = "w_history";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function wallet()
    {
        return $this->belongsTo('App\Models\Bank','bank_id');
    }
    public function dbank()
    {
        return $this->belongsTo('App\Models\Bank','bank_id');
    }    
    public function sub()
    {
        return $this->belongsTo('App\Models\Subaccounts','sub_id');
    }



    private static function buildQueryFilters($userId = 0, $startDate = null, $endDate = null, $status = null, $text = null) {
        $query = DB::table("w_history")
                    ->join('users', 'users.id', '=', 'w_history.user_id')
                    ->leftJoin('compliance', 'users.id', '=', 'compliance.user_id');

        if ($userId > 0) {
            $query->where('w_history.user_id', '=', $userId);
        }

        if (!empty($text)) {
            $textDoc = "%".str_replace(Array(".", "-", "/"), "", $text) . "%";
            $text = "%{$text}%";
            
            $whereRaw = " ( "
                . " (lower(users.first_name) LIKE lower('?') ) OR "
                . " (lower(users.last_name) LIKE lower('?') ) OR "
                . " (lower(users.email) LIKE lower('?') ) OR "
                . " (REPLACE(REPLACE(compliance.cpf, '-', ''), '.', '') = '?' ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.tax_id, '.', ''), '-', ''), '/', '') = '?' ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.document, '.', ''), '-', ''), '/', '') = '?' ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.reg_no, '.', ''), '-', ''), '/', '') = '?' )  "
                . " ) "; 

            $bindings = Array($text, $text, $text, $textDoc, $textDoc, $textDoc, $textDoc);

            $query->whereRaw(DB::raw($whereRaw), $bindings);
        }
        
        if ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
            $query->where('w_history.created_at', '>=', $startDate->format("Y-m-d H:i:s"));
            $query->where('w_history.created_at', '<=', $endDate->format("Y-m-d H:i:s"));
        } else if (!empty($startDate) && !empty($endDate)) {
            $query->where('w_history.created_at', '>=', $startDate);
            $query->where('w_history.created_at', '<=', $endDate);
        }

        if (in_array($status, Array(0, 1, 2))) {
            $query->where('w_history.status', '=', $status);
        }

        return $query; 
    }

    public static function listWithdrawals($userId = 0, $startDate = null, $endDate = null, $status = null, $text = null, $page = 1, $rows = 50) {

        $queryList = self::buildQueryFilters($userId, $startDate, $endDate, $status, $text);
        $total = 0;
        $totalAmount = 0;
        if ($rows > 0) {
            $queryCount = self::buildQueryFilters($userId, $startDate, $endDate, $text);
            $resultCount = $queryCount->selectRaw(DB::raw('count(*) as total, SUM(w_history.amount) as total_amount'))->first();
            
            
            if ($resultCount) {
                $total = $resultCount->total;
                $totalAmount = $resultCount->total_amount;
            }
        }
        if ($page > 0) {
            $page = ($page - 1);
        } else {
            $page = 0;
        }

        $queryList->select('w_history.*', 'users.first_name', 'users.last_name', 'users.email')
                    ->orderBy("w_history.created_at", "DESC");

        if ($rows > 0) {
            $queryList->take($rows)->skip(($page * $rows));
        }
        $withdrawals = $queryList->get();

        return Array("withdrawals" => $withdrawals, "total" => $total, "pages" => ($rows > 0 ? ceil(($total / $rows)) : 1), "totalAmount" => $totalAmount);
    }

    public static function createWithdrawal($userId, $amount, $pixkey, $pixkeyType, $description, $destinatary = null) {
        if (!is_numeric($amount) || !($amount > 0)) {
            throw new \Exception("O valor do saque precisa ser maior que zero.");
        }
        if (empty($pixkey)) {
            throw new \Exception('Você deve informar a chave do PIX.');
        }
        
        if (!in_array($pixkeyType, Array("CPF", "CNPJ", "EMAIL", "PHONE", "EVP"))) {
            throw new \Exception('Você deve informar o tipo de chave pix.');
        }

        $token='ST-'.str_random(32);
        $set=Settings::first();
        $currency = Currency::whereStatus(1)->first();
        $user = User::find($userId);

        if($user->balance < $amount) {
            throw new \Exception('Você não tem saldo suficiente.');
        }

        $userTax = UserTax::getUserTax($userId);
        
        $taxes = number_format( (($amount * ($userTax->withdraw_charge/100))+$userTax->withdraw_chargep), 2, ".", "");
        if ($taxes > $amount) {
            throw new \Exception('O valor solicitado não é suficiente para cobrir as taxas.');
        }
        
        $compliance = Compliance::getOrCreate($user->id);
        $limitVerify = Compliance::validateWithdrawLimit($compliance, $amount, $set);

        if (!$limitVerify->valid) {
            throw new \Exception('O valor solicitado excede o seu limite de saque.');
        }

        if (!$destinatary || empty($destinatary["name"]) || empty($destinatary["document"])) {
            if ($compliance) {
                $document = BillingUtils::getUserDocument($user->id, $compliance);

                $destinatary = Array(
                    "name" => $user->first_name . " " . $user->last_name,
                    "document" => $document,
                    "email" => $user->email
                );
            }
        }

        $sav= Array(
            'user_id' => $user->id,
            'reference' => $token,
            'amount' => number_format(($amount - $taxes), 2, ".", ""),
            "charge" => number_format($taxes, 2, ".", ""),
            "status" => 0,
            "type" => 1,
            "bank_id" => null,
            "next_settlement" => null,
            "method" => "pix",
            "pix_key" => $pixkey,
            "pix_key_type" => $pixkeyType,
            "destinatary_name" => ($destinatary["name"] ?? ""), 
            "destinatary_document" => ($destinatary["document"] ?? ""), 
            "destinatary_email" => ($destinatary["email"] ?? "")
        );
        
        $withdraw = Withdraw::create($sav);

        $oldBalance = $user->balance;
        $user->balance -= $amount;
        $user->save();
 
        if ($amount <= $set->max_automatic_withdraw_value) {
            try {
                $systemGateway = UserGateway::getSystemGateway(UserGateway::PIX_OUT);

                if ($systemGateway->id != 1 || $user->id == 64) {
                    $gateway = UserGateway::getDefaultGateway(UserGateway::PIX_OUT, $user->id);
                
                    if ($gateway->id != 1) {
                        $IPix = PixGateway::getGateway($gateway); 
                        $paymentOrder = $IPix->sendPayment($user, $pixkey, $pixkeyType, $amount, $description);

                        $withdraw->error_msg = "";
                        $withdraw->pix_entoend = "";
                        $withdraw->pix_txid = $paymentOrder->txid;
                    }

                }

                
                $withdraw->save();

            } catch(\Exception $ex) {
                $withdraw->status = 2;
                $withdraw->error_msg = $ex->getMessage();

                $user->balance = $oldBalance;
                $user->save();

                $withdraw->save();
                throw new \Exception($ex->getMessage());
            }


        }

        History::registerHistory($user->id, $amount, $token, 1, 2, $taxes, "WITHDRAW");
        
        if($set->email_notify==1){
            new_withdraw($token);
        }

        return $withdraw;
    }

    
    public function confirmPayment($pixEndToEndId) {

        $current = Withdraw::where("id", $this->id)->first();
        if ($current->status == 1) {
            throw new \Exception("O saque consta aprovado no sistema.");
        }
        if ($current->status == 2) {
            throw new \Exception("O saque consta cancelado no sistema.");
        }
        $this->status = 1;
        $this->paid_at = date("Y-m-d H:i:s");
        $this->pix_entoend = $pixEndToEndId;

        $this->save();

        Charges::registerCharge($this->user_id, $this->reference, $this->charge, "Taxas para o saque {$this->reference}.");

        $user = User::where("id", $this->user_id)->first();

        if (!empty($user->callback_url)) {
            $body = Array(
                "entity" => "WITHDRAW",
                "reference" => $this->reference,
                "receivedValue" => $this->amount,
                "receivedTotal" => ($this->amount + $this->charge)
            );
            Callback::createCallback("WITHDRAW", $this->reference, $this->id, $body, $user->callback_url);
        }
    }



    public function cancelWithdraw($errorMessage) {

        $current = Withdraw::where("id", $this->id)->first();
        if ($current->status == 1) {
            throw new \Exception("O saque consta aprovado no sistema.");
        }
        if ($current->status == 2) {
            throw new \Exception("O saque consta cancelado no sistema.");
        }

        $this->status = 2;
        $this->error_msg = $errorMessage;

        $this->save();

        $user = User::where("id", $this->user_id)->first();
        $user->balance += ($this->amount + $this->charge);
        $user->save();

        if (!empty($user->callback_url)) {
            $body = Array(
                "entity" => "WITHDRAW",
                "reference" => $this->reference,
                "receivedValue" => 0,
                "receivedTotal" => 0
            );

            Callback::createCallback("WITHDRAW", $this->reference, $this->id, $body, $user->callback_url);
        }
    }
}
