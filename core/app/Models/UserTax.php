<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTax extends Model {
    protected $table = "users_taxes";
    protected $guarded = [];

    public function admin() {
        return $this->belongsTo('App\Models\Admin','admin_id');
    }    

    public static function updateUserTaxes($userId, $adminId, $taxes) {

        if (!is_numeric($userId) || !($userId > 0) ) {
            throw new \Exception("É necessário informar a identificação do usuário.");
        }

        if (!is_numeric($adminId) || !($adminId > 0) ) {
            throw new \Exception("É necessário informar a identificação do admin.");
        }

        $tax = self::where("user_id", $userId)->first();

        if ($tax) {
            $tax->use_taxes = (isset($taxes->use_taxes) ? $taxes->use_taxes : 0);
            $tax->transfer_charge = (isset($taxes->transfer_charge) ? $taxes->transfer_charge : 0);
            $tax->transfer_chargep = (isset($taxes->transfer_chargep) ? $taxes->transfer_chargep : 0);
            $tax->merchant_charge = (isset($taxes->merchant_charge) ? $taxes->merchant_charge : 0);
            $tax->merchant_chargep = (isset($taxes->merchant_chargep) ? $taxes->merchant_chargep : 0);
            $tax->invoice_charge = (isset($taxes->invoice_charge) ? $taxes->invoice_charge : 0);
            $tax->invoice_chargep = (isset($taxes->invoice_chargep) ? $taxes->invoice_chargep : 0);
            $tax->product_charge = (isset($taxes->product_charge) ? $taxes->product_charge : 0);
            $tax->product_chargep = (isset($taxes->product_chargep) ? $taxes->product_chargep : 0);
            $tax->single_charge = (isset($taxes->single_charge) ? $taxes->single_charge : 0);
            $tax->single_chargep = (isset($taxes->single_chargep) ? $taxes->single_chargep : 0);
            $tax->donation_charge = (isset($taxes->donation_charge) ? $taxes->donation_charge : 0);
            $tax->donation_chargep = (isset($taxes->donation_chargep) ? $taxes->donation_chargep : 0);
            $tax->bill_charge = (isset($taxes->bill_charge) ? $taxes->bill_charge : 0);
            $tax->bill_chargep = (isset($taxes->bill_chargep) ? $taxes->bill_chargep : 0);
            $tax->withdraw_charge = (isset($taxes->withdraw_charge) ? $taxes->withdraw_charge : 0);
            $tax->withdraw_chargep = (isset($taxes->withdraw_chargep) ? $taxes->withdraw_chargep : 0);
            $tax->days_creditcard_liquidation = (isset($taxes->days_creditcard_liquidation) ? $taxes->days_creditcard_liquidation : 0);
        
            if (!is_numeric($tax->days_creditcard_liquidation) || !($tax->days_creditcard_liquidation > 0)) {
                $tax->days_creditcard_liquidation = 0;
            }
            $tax->admin_id = $adminId;
            $tax->created_at = date("Y-m-d H:i:s");
            $tax->updated_at = date("Y-m-d H:i:s");
            $tax->save();
        } else {
            self::createNewUserConfigs($userId, $adminId, $taxes);
        }
    }

    private static function createNewUserConfigs($userId, $adminId, $taxes) {
        $data = Array(
            "use_taxes" => (isset($taxes->use_taxes) ? $taxes->use_taxes : 0),
            "transfer_charge" => (isset($taxes->transfer_charge) ? $taxes->transfer_charge : 0),
            "transfer_chargep" => (isset($taxes->transfer_chargep) ? $taxes->transfer_chargep : 0),
            "merchant_charge" => (isset($taxes->merchant_charge) ? $taxes->merchant_charge : 0),
            "merchant_chargep" => (isset($taxes->merchant_chargep) ? $taxes->merchant_chargep : 0),
            "invoice_charge" => (isset($taxes->invoice_charge) ? $taxes->invoice_charge : 0),
            "invoice_chargep" => (isset($taxes->invoice_chargep) ? $taxes->invoice_chargep : 0),
            "product_charge" => (isset($taxes->product_charge) ? $taxes->product_charge : 0),
            "product_chargep" => (isset($taxes->product_chargep) ? $taxes->product_chargep : 0),
            "single_charge" => (isset($taxes->single_charge) ? $taxes->single_charge : 0),
            "single_chargep" => (isset($taxes->single_chargep) ? $taxes->single_chargep : 0),
            "donation_charge" => (isset($taxes->donation_charge) ? $taxes->donation_charge : 0),
            "donation_chargep" => (isset($taxes->donation_chargep) ? $taxes->donation_chargep : 0),
            "bill_charge" => (isset($taxes->bill_charge) ? $taxes->bill_charge : 0),
            "bill_chargep" => (isset($taxes->bill_chargep) ? $taxes->bill_chargep : 0),
            "withdraw_charge" => (isset($taxes->withdraw_charge) ? $taxes->withdraw_charge : 0),
            "withdraw_chargep" => (isset($taxes->withdraw_chargep) ? $taxes->withdraw_chargep : 0),
            "use_comissions" => (isset($taxes->use_comissions) ? $taxes->use_comissions : 0),
            "withdraw_comission" => (isset($taxes->withdraw_comission) ? grava_money($taxes->withdraw_comission, 2) : 0),
            "deposit_comission" => (isset($taxes->deposit_comission) ? grava_money($taxes->deposit_comission, 2) : 0),
            "invoice_comission" => (isset($taxes->invoice_comission) ? grava_money($taxes->invoice_comission, 2) : 0),
            "payment_link_comission" => (isset($taxes->payment_link_comission) ? grava_money($taxes->payment_link_comission, 2) : 0),
            "donation_comission" => (isset($taxes->donation_comission) ? grava_money($taxes->donation_comission, 2) : 0),
            "store_comission" => (isset($taxes->store_comission) ? grava_money($taxes->store_comission, 2) : 0),
            "use_payment_configs" => (isset($taxes->use_payment_configs) ? $taxes->use_payment_configs : 0),
            "enable_account_payment" => (isset($taxes->enable_account_payment) ? $taxes->enable_account_payment : 0),
            "enable_boleto_payment" => (isset($taxes->enable_boleto_payment) ? $taxes->enable_boleto_payment : 0),
            "enable_creditcard_payment" => (isset($taxes->enable_creditcard_payment) ? $taxes->enable_creditcard_payment : 0),
            "enable_pix_payment" => (isset($taxes->enable_pix_payment) ? $taxes->enable_pix_payment : 0),
            "admin_id" => $adminId,
            "user_id" => $userId,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        );

        self::create($data);
    }

    public static function updateComissions($userId, $adminId, $taxes) {

        if (!is_numeric($userId) || !($userId > 0) ) {
            throw new \Exception("É necessário informar a identificação do usuário.");
        }

        if (!is_numeric($adminId) || !($adminId > 0) ) {
            throw new \Exception("É necessário informar a identificação do admin.");
        }

        $tax = self::where("user_id", $userId)->first();

        if ($tax) {
            $tax->use_comissions = (isset($taxes->use_comissions) ? $taxes->use_comissions : 0);
            $tax->withdraw_comission = (isset($taxes->withdraw_comission) ? grava_money($taxes->withdraw_comission, 2) : 0);
            $tax->deposit_comission = (isset($taxes->deposit_comission) ? grava_money($taxes->deposit_comission, 2) : 0);
            $tax->invoice_comission = (isset($taxes->invoice_comission) ? grava_money($taxes->invoice_comission, 2) : 0);
            $tax->payment_link_comission = (isset($taxes->payment_link_comission) ? grava_money($taxes->payment_link_comission, 2) : 0);
            $tax->donation_comission = (isset($taxes->donation_comission) ? grava_money($taxes->donation_comission, 2) : 0);
            $tax->store_comission = (isset($taxes->store_comission) ? grava_money($taxes->store_comission, 2): 0);
            $tax->admin_id = $adminId;
            $tax->created_at = date("Y-m-d H:i:s");
            $tax->updated_at = date("Y-m-d H:i:s");
            $tax->save();
        } else {
            self::createNewUserConfigs($userId, $adminId, $taxes);
        }
    }
    

    public static function getUserTax($userId) {

        if (!is_numeric($userId) || !($userId > 0) ) {
            throw new \Exception("É necessário informar a identificação do usuário.");
        }

        $tax = self::where("user_id", $userId)->first();
        $set = Settings::first();
        
        return (Object) Array(
            "transfer_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->transfer_charge : $tax->transfer_charge),
            "transfer_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->transfer_chargep : $tax->transfer_chargep),
            "merchant_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->merchant_charge : $tax->merchant_charge),
            "merchant_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->merchant_chargep : $tax->merchant_chargep),
            "invoice_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->invoice_charge : $tax->invoice_charge),
            "invoice_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->invoice_chargep : $tax->invoice_chargep),
            "product_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->product_charge : $tax->product_charge),
            "product_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->product_chargep : $tax->product_chargep),
            "single_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->single_charge : $tax->single_charge),
            "single_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->single_chargep : $tax->single_chargep),
            "donation_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->donation_charge : $tax->donation_charge),
            "donation_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->donation_chargep : $tax->donation_chargep),
            "bill_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->bill_charge : $tax->bill_charge),
            "bill_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->bill_chargep : $tax->bill_chargep),
            "withdraw_charge" => ((!$tax || $tax->use_taxes < 1) ? $set->withdraw_charge : $tax->withdraw_charge),
            "withdraw_chargep" => ((!$tax || $tax->use_taxes < 1) ? $set->withdraw_chargep : $tax->withdraw_chargep),
            "withdraw_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->withdraw_comission : $tax->withdraw_comission),
            "deposit_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->deposit_comission : $tax->deposit_comission),
            "invoice_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->invoice_comission : $tax->invoice_comission),
            "payment_link_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->payment_link_comission : $tax->payment_link_comission),
            "donation_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->donation_comission : $tax->donation_comission),
            "store_comission" => ((!$tax || $tax->use_comissions < 1) ? $set->store_comission : $tax->store_comission),
            "days_creditcard_liquidation" => ((!$tax || $tax->days_creditcard_liquidation < 1) ? $set->days_creditcard_liquidation : $tax->days_creditcard_liquidation),
            "enable_account_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_account_payment : $tax->enable_account_payment),
            "enable_boleto_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_boleto_payment : $tax->enable_boleto_payment),
            "enable_creditcard_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_creditcard_payment : $tax->enable_creditcard_payment),
            "enable_pix_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_pix_payment : $tax->enable_pix_payment)
        );
    }



    public static function updatePaymentMethodsConfig($userId, $adminId, $taxes) {
        
        if (!is_numeric($userId) || !($userId > 0) ) {
            throw new \Exception("É necessário informar a identificação do usuário.");
        }

        if (!is_numeric($adminId) || !($adminId > 0) ) {
            throw new \Exception("É necessário informar a identificação do admin.");
        }

        $tax = self::where("user_id", $userId)->first();

        if ($tax) {
            $tax->use_payment_configs = (isset($taxes->use_payment_configs) ? $taxes->use_payment_configs : 0);
            $tax->enable_account_payment = (isset($taxes->enable_account_payment) ? $taxes->enable_account_payment : 0);
            $tax->enable_boleto_payment = (isset($taxes->enable_boleto_payment) ? $taxes->enable_boleto_payment : 0);
            $tax->enable_creditcard_payment = (isset($taxes->enable_creditcard_payment) ? $taxes->enable_creditcard_payment : 0);
            $tax->enable_pix_payment = (isset($taxes->enable_pix_payment) ? $taxes->enable_pix_payment : 0);
            $tax->admin_id = $adminId;
            $tax->created_at = date("Y-m-d H:i:s");
            $tax->updated_at = date("Y-m-d H:i:s");
            $tax->save();
        } else {
            self::createNewUserConfigs($userId, $adminId, $taxes);
        }
    }


    public static function getUserPaymentConfigs($userId) {

        if (!is_numeric($userId) || !($userId > 0) ) {
            throw new \Exception("É necessário informar a identificação do usuário.");
        }

        $tax = self::where("user_id", $userId)->first();
        $set = Settings::first();

        return (Object) Array(
            "enable_account_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_account_payment : $tax->enable_account_payment),
            "enable_boleto_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_boleto_payment : $tax->enable_boleto_payment),
            "enable_creditcard_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_creditcard_payment : $tax->enable_creditcard_payment),
            "enable_pix_payment" => ((!$tax || $tax->use_payment_configs < 1) ? $set->enable_pix_payment : $tax->enable_pix_payment)
        );

    }
}