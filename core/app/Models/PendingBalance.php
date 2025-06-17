<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingBalance extends Model {
    protected $table = "pending_balances";
    protected $guarded = [];

    const ENTITY_TYPE = Array(
        "INVOICE" => Array("value" => "INVOICE", "text" => "Fatura"),
        "FUND" => Array("value" => "FUND", "text" => "Recarga"),
        "SINGLE_CHARGE" => Array("value" => "SINGLE_CHARGE", "text" => "Link de Pagamento"),
        "DONATION" => Array("value" => "DONATION", "text" => "Doação"),
        "STORE" => Array("value" => "STORE", "text" => "Loja")
    );

    const INVOICE = "INVOICE";
    const FUND = "FUND";
    const SINGLE_CHARGE = "SINGLE_CHARGE";
    const DONATION = "DONATION";
    const STORE = "STORE";

    
    public static function saveBalance($userId, $amount, $charge, $entityType, $entityRef, $description) {

        $userTaxes = UserTax::getUserTax($userId);

        $liquidationDate = new \DateTime();
        $liquidationDate->add(new \DateInterval("P{$userTaxes->days_creditcard_liquidation}D"));

        $data = Array(
            "user_id" => $userId,
            "entity_type" => $entityType,
            "entity_ref" => $entityRef,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
            "amount" => number_format($amount, 2, ".", ""),
            "charge" => number_format($charge, 2, ".", ""),
            "liquidation_date" => $liquidationDate->format("Y-m-d") . " 00:00:00",
            "liquidated" => 0,
            "description" => $description
        );

        self::create($data);
    }
}
