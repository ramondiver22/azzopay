<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


class AddressController extends Controller {
   private $successStatus  = 200;
    
    public function getAddressByCep() {
        
        try {
            
            $cep = request("cep");
            $address = find_address_by_cep($cep);
            
            
            $json["address"] = $address;
            $json["success"] = true;
        } catch (\Exception $ex) {
            $json["sucesso"] = false;
            $json["message"] = $ex->getMessage();
        }
        
        print json_encode($json);
        
    }
}