<?php

namespace App\Lib\AccountPayment;

use App\Lib\AccountPayment\Banks\PlatformAccount;

class AccountGateway {
    
    public static function getGateway($gateway) {
        if ($gateway == null) {
            throw new \Exception("Você precisa informar um gateway válido.");
        }

        switch($gateway->id) {
            case 507: 
                $account = new PlatformAccount($gateway);
                return $account;

            default:
               throw new \Exception("Gateway inválido ou não cadastrado."); 
        }
    }

}