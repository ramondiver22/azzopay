<?php

namespace App\Lib\BancoOriginal;

use App\Models\Gateway;

class BancoOriginalGateway  {
    
    public static function getOriginalGateway() {
        
        $gateway = Gateway::find(508);
        return $gateway;
    }
    
}