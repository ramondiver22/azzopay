<?php

namespace App\Lib\Boleto;

use App\Lib\Boleto\Banks\BoletoCielo;

class BoletoGateway {
    
    public static function getGateway($gateway) {
        if ($gateway == null) {
            throw new \Exception("Você precisa informar um gateway válido.");
        }

        switch($gateway->id) {
            case 507: 
                $boleto = new BoletoCielo($gateway);
                return $boleto;

            default:
               throw new \Exception("Gateway inválido ou não cadastrado."); 
        }
    }

}