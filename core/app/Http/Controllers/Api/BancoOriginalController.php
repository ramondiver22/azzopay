<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Compliance;
use App\Lib\BancoOriginal\BancoOriginalAccount;
use App\Models\Bank;
use App\Models\Banksupported;


class BancoOriginalController extends Controller {
   private $successStatus  = 200;
    
    public function register() {
        
        try {
            
            $user = Auth::user();
            $compliance = Compliance::where("user_id", $user->id)->first();
            
            $bankAccount = Bank::where("user_id", $user->id)->orderBy("routing_number", "asc")->take(1)->first();
            
            if (!$bankAccount) {
                throw new Exception("You need to input an bank account");
            }
            
            $bankSupported = Banksupported::where("id", $bankAccount->bank_id)->first();
            
            $bancoOriginalAccount = new BancoOriginalAccount();
            $result = $bancoOriginalAccount->createAccount($compliance, $bankAccount, $bankSupported);
            
            $json["result"] = $result;
            $json["success"] = true;
        } catch (\Exception $ex) {
            $json["sucesso"] = false;
            $json["message"] = $ex->getMessage();
        }
        
        print json_encode($json);
        
    }
}