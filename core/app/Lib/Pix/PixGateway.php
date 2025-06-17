<?php

namespace App\Lib\Pix;

use App\Lib\Pix\Banks\PixBancoBrasil;
use App\Lib\Pix\Banks\PixTreealv;
use App\Lib\Pix\Banks\PixFitbank;

class PixGateway {
    
    public static function getGateway($gateway) {
        
        if ($gateway == null) {
            throw new \Exception("Você precisa informar um gateway válido.");
        }

        switch($gateway->id) {
            case 509: 
                $pix = new PixBancoBrasil($gateway->val2, $gateway->val1, $gateway->val3, $gateway->val4, $gateway->sandbox > 0);
                return $pix;
            case 510: 
                $pix = new PixFitbank($gateway->val1, $gateway->val2, $gateway->val3, $gateway->sandbox > 0);
                return $pix;
            case 511: 
                $pix = new PixTreealv($gateway->val1, $gateway->sandbox > 0);
                return $pix;

            default:
               throw new \Exception("Gateway inválido ou não cadastrado."); 
        }


    }

}