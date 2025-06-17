<?php

namespace App\Lib\CreditCard;

use App\Lib\CreditCard\Banks\CreditCardCielo;

class CreditCardGateway {
    
    public static function getGateway($gateway) {
        if ($gateway == null) {
            throw new \Exception("Você precisa informar um gateway válido.");
        }

        switch($gateway->id) {
            case 507: 
                $creditCard = new CreditCardCielo($gateway);
                return $creditCard;

            default:
               throw new \Exception("Gateway inválido ou não cadastrado."); 
        }
    }

}