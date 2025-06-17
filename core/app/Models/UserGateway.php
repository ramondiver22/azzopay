<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Cielo_lib;

class UserGateway extends Model {
    protected $table = "users_gateways";
    protected $guarded = [];
    
    const ACCOUNT = "ACCOUNT";
    const CREDIT_CARD = "CREDIT_CARD";
    const DEBIT_CARD = "DEBIT_CARD";
    const BOLETO = "BOLETO";
    const PIX = "PIX";
    const PIX_OUT = "PIX_OUT";
    
    
    /**
     * Return default gateway for the user and operation type
     * @param type $type
     * @param type $userId
     */
    public static function getDefaultGateway($type, $userId) {
        
        if (!in_array(strtoupper($type), Array(self::CREDIT_CARD, self::DEBIT_CARD, self::BOLETO, self::PIX, self::PIX_OUT))) {
            throw new \Exception("Invalid gateway operation.");
        }
        $userGateway = null;
        if ($userId != null) {
            $userGateway = self::where("type", strtoupper($type))->where("user_id", $userId)->first();
        }
        
        if ($userGateway == null) {
            $userGateway = self::where("type", strtoupper($type))->whereNull('user_id')->first();
        }
        
        $gateway = Gateway::where("id", $userGateway->gateway_id)->first();
        
        return $gateway;
        
    }

    /**
     * Return default gateway for the user and operation type
     * @param type $type
     * @param type $userId
     */
    public static function getSystemGateway($type) {
        
        if (!in_array(strtoupper($type), Array(self::CREDIT_CARD, self::DEBIT_CARD, self::BOLETO, self::PIX, self::PIX_OUT))) {
            throw new \Exception("Invalid gateway operation.");
        }
        $userGateway = self::where("type", strtoupper($type))->whereNull('user_id')->first();
        
        $gateway = Gateway::where("id", $userGateway->gateway_id)->first();
        
        return $gateway;
        
    }



    public static function  validatePaymentMethod($userId, $paymentMethod) {

        $userTax = UserTax::getUserTax($userId);

        switch($paymentMethod) {
            case self::CREDIT_CARD: 
                return ($userTax->enable_creditcard_payment > 0);
            case self::BOLETO: 
                return ($userTax->enable_boleto_payment > 0);
            case self::PIX: 
                return ($userTax->enable_pix_payment > 0);
            case self::ACCOUNT: 
                return ($userTax->enable_account_payment > 0);
        }
        
        return false;
    }

}
