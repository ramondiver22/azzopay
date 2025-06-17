<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Transactions;
use App\Models\Invoice;
use App\Models\Audit;
use App\Models\History;
use App\Models\User;
use App\Models\Settings;
use App\Models\Charges;

class Invoice extends Model {
    protected $table = "invoices";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }   
    public function sender()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }    



    private static function buildQueryFilters($userId = 0, $status = "T", $search = null, $startDate = null, $endDate = null) {


        $query = DB::table("invoices")
                    ->join('users', 'users.id', '=', 'invoices.user_id')
                    ->leftJoin('compliance', 'users.id', '=', 'compliance.user_id');

        if ($userId > 0) {
            $query->where('invoices.user_id', '=', $userId);
        }

        if (in_array($status, array(0, 1, 2, 3))) {
            $query->where('invoices.status', '=', $status);
        }

        if (!empty($search)) {
            $textDoc = "%".str_replace(Array(".", "-", "/"), "", $search) . "%";
            $text = "%{$search}%";
            
            $whereRaw = " ( "
                . " (lower(invoices.ref_id) LIKE lower(?) ) OR "
                . " (lower(invoices.invoice_no) LIKE lower(?) ) OR "
                . " (lower(users.first_name) LIKE lower(?) ) OR "
                . " (lower(users.last_name) LIKE lower(?) ) OR "
                . " (lower(users.email) LIKE lower(?) ) OR "
                . " (REPLACE(REPLACE(compliance.cpf, '-', ''), '.', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.tax_id, '.', ''), '-', ''), '/', '') = ?) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.document, '.', ''), '-', ''), '/', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.reg_no, '.', ''), '-', ''), '/', '') = ? )  "
                . " ) "; 

            $bindings = Array($text, $text, $text, $text, $text, $textDoc, $textDoc, $textDoc, $textDoc);

            $query->whereRaw(DB::raw($whereRaw), $bindings);
        }
        
        if ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
            $query->where('invoices.created_at', '>=', $startDate->format("Y-m-d H:i:s"));
            $query->where('invoices.created_at', '<=', $endDate->format("Y-m-d H:i:s"));
        } else if (!empty($startDate) && !empty($endDate)) {
            $query->where('invoices.created_at', '>=', $startDate);
            $query->where('invoices.created_at', '<=', $endDate);
        }

        return $query; 
    }
    
    public static function listInvoices($userId = 0, $status = "T", $search = null, $start = null, $end = null, $page = 1, $rows = 50) {
        $where = Array();
        
        $queryList = self::buildQueryFilters($userId, $status, $search, $start, $end);
        //\DB::enableQueryLog();
        $total = 0;
        if ($rows > 0) {
            $queryCount = self::buildQueryFilters($userId, $status, $search, $start, $end);
            $resultCount = $queryCount->selectRaw(
                DB::raw(
                    "count(*) as total "
                )
            )->first();
            //exit(print_r(DB::getQueryLog()));
            if ($resultCount) {
                $total = $resultCount->total;
            }
        }
        if ($page > 0) {
            $page = ($page - 1);
        } else {
            $page = 0;
        }

        $queryList->select('invoices.*', 'users.first_name', 'users.last_name')
                    ->orderBy("invoices.created_at", "DESC");

        if ($rows > 0) {
            $queryList->take($rows)->skip(($page * $rows));
        }
        $invoices = $queryList->get();

        return Array(
            "invoices" => $invoices, 
            "total" => $total, 
            "pages" => ($rows > 0 ? ceil(($total / $rows)) : 1)
        );

    }
    
    public static function getInvoiceStatus($statusCode) {
        switch ($statusCode) {
            case 0: return "PENDING";
            case 1: return "PAID";
            case 2: return "CANCELED";
            case 3: return "REFUNDED";
            default: 
                return "";
        }
    }
    

    public function proccessPayment($paymentInfo, $paymentMethod, $sender = null) {

        if ($this->status == 1) {
            throw new \Exception("Invoice already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Invoice already canceled.");
        }

        $set = Settings::first();
        $user= User::whereId($this->user_id)->first();

        $userTax = UserTax::getUserTax($this->user_id);
        if ($paymentInfo->status == 1) {

            $this->received_total += $paymentInfo->totalReceived;

            $total = $paymentInfo->totalReceived;
            $charge = 0;
            $amount = 0;
            if (strtolower($paymentInfo->paymentMethod) == "creditcard") {
                $charge = $this->received_total - $this->total;
                $amount = $this->total;
            } else {
                $charge = $this->received_total * $userTax->invoice_charge / 100 + ($userTax->invoice_chargep);
                $amount = $total - $charge;
            }
            

            if ($this->received_total >= $this->total) {
                $this->status=1;
            }

            $this->charge += $charge;
            $this->save();

            $transaction = Transactions::proccessInvoicePayment($this, $paymentInfo, $paymentMethod, $sender);

            
            $logDescription = 'Payment for Invoice - '.$this->ref_id.' was successful';
            Audit::registerLog($user->id, $this->ref_id, $logDescription);

            if (strtolower($paymentInfo->paymentMethod) == "creditcard") { 
                PendingBalance::saveBalance($user->id, $amount, $charge, PendingBalance::INVOICE, $this->ref_id, "Recebimento de invoice {$this->id} ");
            } else {
                $user->updateBalance($user, $amount, "credit");
                $chargeDescription = 'Charges for invoice #' . $this->ref_id;
                Charges::registerCharge($user->id, $this->ref_id, $charge, $chargeDescription);
                         
                History::registerHistory($user->id, $amount, $this->ref_id, 0, 1, 0, "INVOICE");
            }
            //Notify Users
            if($set->email_notify==1){
                send_invoicereceipt($this->ref_id, strtolower($paymentMethod), $transaction->ref_id);
            }


            if (!empty($user->callback_url)) {
                $body = Array(
                    "entity" => "INVOICE",
                    "reference" => $this->ref_id,
                    "receivedValue" => $paymentInfo->totalReceived,
                    "receivedTotal" => $this->received_total
                );
                Callback::createCallback("INVOICE", $this->ref_id, $this->id, $body, $user->callback_url);
            }
        } else if ($paymentInfo->status == 2) {

        }
    }



    public function proccessPaymentWithAccountBalance($userClient) {

        if ($this->status == 1) {
            throw new \Exception("Invoice already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Invoice already canceled.");
        }

        if ($userClient->id == $this->user_id) {
            throw new \Exception("Você não pode pagar uma invoice gerada em sua própria conta.");
        }

        $set = Settings::first();
        $user= User::whereId($this->user_id)->first();

        $userTax = UserTax::getUserTax($this->user_id);
        if($this->total > $user->balance) {
            throw new \Exception("Saldo insuficiente.");
        }

        $this->received_total += $this->total;
        $this->status=1;
        $this->charge = ($this->total * $userTax->invoice_charge / 100 + ($userTax->invoice_chargep));
        $amount = $this->total - $this->charge;

        $this->save();
        $user->updateBalance($user, $amount, "credit");
        $userClient->updateBalance($user, $this->total, "debit");

        $paymentInfo = (Object) Array(
            "paymentMethod" => "account",
            "status" => 1,
            "totalReceived" => number_format($this->total, 2, ".", ""),
            "gateway" => "account"
        );

        $transaction = Transactions::proccessInvoicePayment($this, $paymentInfo, "account", $userClient);

        $logDescription = 'Payment for Invoice - '.$this->ref_id.' was successful';
        Audit::registerLog($user->id, $this->ref_id, $logDescription);

        $chargeDescription = 'Charges for invoice #' . $this->ref_id;
        Charges::registerCharge($user->id, $this->ref_id, $this->charge, $chargeDescription);

        History::registerHistory($user->id, $amount, $this->ref_id, 0, 1);
        History::registerHistory($userClient->id, $this->total, $this->ref_id, 0, 2);

        //Notify Users
        if($set->email_notify==1){
            send_invoicereceipt($this->ref_id, strtolower($paymentMethod), $transaction->ref_id);
        }

        if (!empty($user->callback_url)) {
            $body = Array(
                "entity" => "INVOICE",
                "reference" => $this->ref_id,
                "receivedValue" => $paymentInfo->totalReceived,
                "receivedTotal" => $this->received_total
            );
            Callback::createCallback("INVOICE", $this->ref_id, $this->id, $body, $user->callback_url);
        }

    }
}
