<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Charges extends Model {
    
    protected $table = "charges";
    protected $guarded = [];
    
    public static function registerCharge($userId, $trx, $amount, $description) {

        $data = Array(
            "user_id" => $userId,
            "ref_id" => $trx,
            "amount" => $amount,
            "log" => $description
        );

        self::create($data);

        if ($userId > 0) {
            $user = User::where("id", $userId)->first();

            if ($user->enroller > 0) {
                
                $enrollerTax = UserTax::getUserTax($user->enroller);

                $percent = 0;
                $description = "Comissao sobre taxa de ";
                switch(substr($trx, 0, strpos($trx, "-"))) {
                    case "ST": 
                        $percent = ($enrollerTax->withdraw_comission / 100);
                        $description .= " saque ";
                        break;
                    case "FUD": 
                        $percent = ($enrollerTax->deposit_comission / 100);
                        $description .= " recarga ";
                        break;
                    case "INV": 
                        $percent = ($enrollerTax->invoice_comission / 100);
                        $description .= " invoice ";
                        break;
                    case "SC": 
                        $percent = ($enrollerTax->payment_link_comission / 100);
                        $description .= " link de pagamento ";
                        break;
                    case "DN": 
                        $percent = ($enrollerTax->donation_comission / 100);
                        $description .= " doação ";
                        break;
                    case "ORD": 
                        $percent = ($enrollerTax->store_comission / 100);
                        $description .= " venda ";
                        break;
                }

                $description .= " {$trx} ";

                if ($percent > 0) {
                    $value = number_format(($amount * $percent), 2, ".", "");

                    $enroller = User::where("id", $user->enroller)->first();
                    $user->updateBalance($enroller, $value, "credit");
                    Audit::registerLog($enroller->id, $trx, $description);
                    History::registerHistory($enroller->id, $value, $trx, 0, 1, 0, 'COMISSION');
                }

            }
        }

    }

    private static function buildQueryFilters($userId = 0, $startDate = null, $endDate = null, $text = null) {
        $query = DB::table("charges")
                    ->join('users', 'users.id', '=', 'charges.user_id')
                    ->leftJoin('compliance', 'users.id', '=', 'compliance.user_id');

        if ($userId > 0) {
            $query->where('charges.user_id', '=', $userId);
        }

        if (!empty($text)) {
            $textDoc = "%".str_replace(Array(".", "-", "/"), "", $text) . "%";
            $text = "%{$text}%";
            
            $whereRaw = " ( "
                . " (lower(users.first_name) LIKE lower(?) ) OR "
                . " (lower(users.last_name) LIKE lower(?) ) OR "
                . " (lower(users.email) LIKE lower(?) ) OR "
                . " (REPLACE(REPLACE(compliance.cpf, '-', ''), '.', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.tax_id, '.', ''), '-', ''), '/', '') = ?) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.document, '.', ''), '-', ''), '/', '') = ? ) OR "
                . " (REPLACE(REPLACE(REPLACE(compliance.reg_no, '.', ''), '-', ''), '/', '') = ? )  "
                . " ) "; 

            $bindings = Array($text, $text, $text, $textDoc, $textDoc, $textDoc, $textDoc);

            $query->whereRaw(DB::raw($whereRaw), $bindings);
        }
        
        if ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
            $query->where('charges.created_at', '>=', $startDate->format("Y-m-d H:i:s"));
            $query->where('charges.created_at', '<=', $endDate->format("Y-m-d H:i:s"));
        } else if (!empty($startDate) && !empty($endDate)) {
            $query->where('charges.created_at', '>=', $startDate);
            $query->where('charges.created_at', '<=', $endDate);
        }

        return $query; 
    }

    public static function listCharges($userId = 0, $startDate = null, $endDate = null, $text = null, $page = 1, $rows = 50) {

        $queryList = self::buildQueryFilters($userId, $startDate, $endDate, $text);
        $total = 0;
        $totalAmount = 0;
        if ($rows > 0) {
            $queryCount = self::buildQueryFilters($userId, $startDate, $endDate, $text);
            $resultCount = $queryCount->selectRaw(DB::raw('count(*) as total, SUM(charges.amount) as total_amount'))->first();
            
            
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

        $queryList->select('charges.*', 'users.first_name', 'users.last_name')
                    ->orderBy("charges.created_at", "DESC");

        if ($rows > 0) {
            $queryList->take($rows)->skip(($page * $rows));
        }
        $charges = $queryList->get();

        return Array("charges" => $charges, "total" => $total, "pages" => ($rows > 0 ? ceil(($total / $rows)) : 1), "totalAmount" => $totalAmount);
    }

}
