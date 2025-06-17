<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History extends Model {
    protected $table = "history";
    protected $guarded = [];

    const ENTITIES = Array('WITHDRAW','FUND','DONATION','PAYMENT_LINK','COMISSION','ORDER','INVOICE');

    public static function getStringEntity($entity) {
        switch($entity) {
            case "WITHDRAW": return "Saque";
            case "FUND": return "Recarga de Saldo";
            case "DONATION": return "Doação";
            case "PAYMENT_LINK": return "Link de Pagamento";
            case "COMISSION": return "Comissão";
            case "ORDER": return "Venda";
            case "INVOICE": return "Fatura";
        }
    }

    public static function getStringType($type) {
        switch($type) {
            case 1: return "Entrada";
            case 2: return "Saída";
        }
    }

    public static function getLabelType($type) {
        switch($type) {
            case 1: return "success";
            case 2: return "danger";
        }
    }

    public static function registerHistory($userId, $amount, $trx, $main, $type, $charge = 0, $entityType = "FUND") {

        $entityType = "FUND";

        switch(substr($main, 0, strpos($main, "-"))) {
            case "ST": 
                $entityType = "WITHDRAW";
                break;
            case "FUD": 
                $entityType = "FUND";
                break;
            case "INV": 
                $entityType = "INVOICE";
                break;
            case "SC": 
                $entityType = "PAYMENT_LINK";
                break;
            case "DN": 
                $entityType = "DONATION";
                break;
            case "ORD": 
                $entityType = "FUORDERND";
                break;
                
        }
        $data = Array(
            "user_id" => $userId,
            "amount" => $amount,
            "ref" => $trx,
            "main" => $main,
            "type" => $type,
            "charge" => $charge,
            "entity_type" => $entityType
        );

        self::create($data);
    }


    private static function buildQueryFilters($userId = 0, $startDate = null, $endDate = null, $text = null, $type = null, $entity = null) {
        $query = DB::table("history")
                    ->join('users', 'users.id', '=', 'history.user_id')
                    ->leftJoin('compliance', 'users.id', '=', 'compliance.user_id');

        if ($userId > 0) {
            $query->where('history.user_id', '=', $userId);
        }

        if ($type > 0) {
            $query->where('history.type', '=', $type);
        }

        if (!empty($entity)) {
            if (in_array($entity, self::ENTITIES)) {
                $query->where('history.entity_type', '=', $entity);
            }
        }

        if (!empty($text)) {
            $textDoc = "%".str_replace(Array(".", "-", "/"), "", $text) . "%";
            $text = "%{$text}%";
            
            $whereRaw = " ( "
                . " (lower(users.first_name) LIKE lower(?) ) OR "
                . " (lower(users.last_name) LIKE lower(?) ) OR "
                . " (lower(users.email) LIKE lower(?) ) OR "
                . " (REPLACE(REPLACE(compliance.cpf, '-', ''), '.', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.tax_id, '.', ''), '-', ''), '/', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.document, '.', ''), '-', ''), '/', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.reg_no, '.', ''), '-', ''), '/', '') = ?)  "
                . " ) "; 

            $bindings = Array($text, $text, $text, $textDoc, $textDoc, $textDoc, $textDoc);

            $query->whereRaw(DB::raw($whereRaw), $bindings);
        }
        
        if ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
            $query->where('history.created_at', '>=', $startDate->format("Y-m-d H:i:s"));
            $query->where('history.created_at', '<=', $endDate->format("Y-m-d H:i:s"));
        } else if (!empty($startDate) && !empty($endDate)) {
            $query->where('history.created_at', '>=', $startDate);
            $query->where('history.created_at', '<=', $endDate);
        }

        return $query; 
    }

    public static function listHistoric($userId = 0, $startDate = null, $endDate = null, $text = null,  $type = null, $entity = null, 
                                        $page = 1, $rows = 50) {

        $queryList = self::buildQueryFilters($userId, $startDate, $endDate, $text, $type, $entity);
        $total = 0;

        $totalCredits = 0;
        $totalDebits = 0;
        $totalAmount = 0;

        if ($rows > 0) {
            $queryCount = self::buildQueryFilters($userId, $startDate, $endDate, $text, $type, $entity);
            $resultCount = $queryCount->selectRaw(
                DB::raw(
                    "count(*) as total, " 
                    . "SUM( CASE WHEN type = 1 THEN history.amount WHEN type = 2 THEN 0 END ) as credits, "
                    . "SUM( CASE WHEN type = 1 THEN 0 WHEN type = 2 THEN history.amount END ) as debits, "
                    . "SUM( CASE WHEN type = 1 THEN history.amount WHEN type = 2 THEN(history.amount * (-1)) END ) as total_amount"
                )
            )->first();
            
            if ($resultCount) {
                $total = $resultCount->total;
                $totalCredits = $resultCount->credits;
                $totalDebits = $resultCount->debits;
                $totalAmount = $resultCount->total_amount;
            }
        }
        if ($page > 0) {
            $page = ($page - 1);
        } else {
            $page = 0;
        }

        $queryList->select('history.*', 'users.first_name', 'users.last_name')
                    ->orderBy("history.created_at", "DESC");

        if ($rows > 0) {
            $queryList->take($rows)->skip(($page * $rows));
        }
        $history = $queryList->get();

        return Array(
            "historic" => $history, 
            "total" => $total, 
            "pages" => ($rows > 0 ? ceil(($total / $rows)) : 1), 
            "totalAmount" => $totalAmount, 
            "totalCredits" => $totalCredits, 
            "totalDebits" => $totalDebits
        );
    }

    
}
