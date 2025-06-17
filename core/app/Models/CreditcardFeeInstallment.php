<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditcardFeeInstallment extends Model {
    protected $table = "creditcard_fee_installment";
    protected $guarded = [];


    

    public static function saveInstallment($creditcardFeeTable, $installment, $tax, $taxPercent) {
        
        if (!($installment > 0)) {
            throw new \Exception("Parcela inválida.");
        }

        if(!is_numeric($tax) || $tax < 0) {
            throw new \Exception("Taxa fixa inválida.");
        }

        if(!is_numeric($taxPercent) || $taxPercent < 0) {
            throw new \Exception("Taxa percentual inválida.");
        }
        
        $data = Array(
            "installments" => $installment,
            "tax" => number_format($tax, 2, ".", ""),
            "tax_p" => number_format($taxPercent, 2, ".", ""),
            "updated_at" => date("Y-m-d H:i:s")
        );

        $creditcardFeeInstallment = self::where('creditcard_fee_table_id', $creditcardFeeTable->id)->where("installments", $installment)->first();
        if ($creditcardFeeInstallment) {
            foreach($data as $attribute=>$value) {
                $creditcardFeeInstallment->$attribute = $value;
            }
            $creditcardFeeInstallment->save();;
        } else {
            $data["creditcard_fee_table_id"] = $creditcardFeeTable->id;
            $data["created_at"] = date("Y-m-d H:i:s");
            $creditcardFeeInstallment = self::create($data);
        }

        return $creditcardFeeInstallment;
    }

    public static function getInstallmentsByTable($creditcardFeeTable) {
        return self::where("creditcard_fee_table_id", $creditcardFeeTable->id)->orderBy("installments")->get();
    }

    public static function getInstallmentFee($creditcardFeeTable, $installment) {
        return self::where("creditcard_fee_table_id", $creditcardFeeTable->id)->where("installments", $installment)->first();
    }


    public static function calcInstalmentTotal($creditcardFeeTable, $installment, $amount) {
        $installmentFee = self::getInstallmentFee($creditcardFeeTable, $installment);

        if ($installmentFee) {
            return number_format(($amount + $installmentFee->tax + ($amount * $installmentFee->tax_p / 100) ), 2, ".", "");
        } else {
            return $amount;
        }
    }

}