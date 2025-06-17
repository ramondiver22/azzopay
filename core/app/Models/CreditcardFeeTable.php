<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditcardFeeTable extends Model {
    protected $table = "creditcard_fee_table";
    protected $guarded = [];

    
    public static function getDefaultTable() {
        $creditcardFeeTable = CreditcardFeeTable::whereNull('user_id')->first();
        if (!$creditcardFeeTable) {
            $creditcardFeeTable = self::create(Array(
                "user_id" => null,
                "use_table" => 1,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ));
        }

        return $creditcardFeeTable;
    }


    public static function getUserTable($userId) {

        $creditcardFeeTable = CreditcardFeeTable::where('user_id', $userId)->first();

        if (!$creditcardFeeTable) {
            $creditcardFeeTable = self::getDefaultTable();
        }

        return $creditcardFeeTable;
    }

    public static function getOrCreateUserTable($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("O id do usuário deve ser informado.");
        }

        $creditcardFeeTable = CreditcardFeeTable::where('user_id', $userId)->first();

        if (!$creditcardFeeTable) {
            $creditcardFeeTable = self::create(Array(
                "user_id" => $userId,
                "use_table" => 1,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ));
        }

        return $creditcardFeeTable;
    }

    public static function getUserInstallmentsTable($amount, $userId = null) {
        $creditcardFeeTable = null;
        
        if ($userId > 0) {
            $creditcardFeeTable = CreditcardFeeTable::where('user_id', $userId)->first();
        }

        if (!$creditcardFeeTable || ($creditcardFeeTable->use_table < 1)) {
            $creditcardFeeTable = CreditcardFeeTable::whereNull('user_id')->first();
        }

        $fees = CreditcardFeeInstallment::where("creditcard_fee_table_id", $creditcardFeeTable->id)->orderBy("installments")->get();

        $table = Array();

        foreach($fees as $f) {

            $totalValue = number_format(($amount + $f->tax + ($amount * $f->tax_p / 100)), 2, ".", "");
            $installmentValue = number_format(($totalValue / $f->installments), 2, ".", "");

            $table[] = Array(
                "installment" => $f->installments,
                "installmentValue" => $installmentValue,
                "totalValue" => $totalValue,
            );
            
        }
        
        return $table;
    }

}