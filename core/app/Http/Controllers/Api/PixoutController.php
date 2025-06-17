<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Validator;

class PixoutController extends Controller {
    private $successStatus  = 200;
    
    public function create(Request $request) {
        
        try {
            
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'pixkey' => 'required|string',
                'keytype' => 'required|string',
                'client' => 'string',
                'document' => 'string',
                'email' => 'string|email',
                'description' => 'string'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if($user->email==request("email")){
                throw new \Exception("Invalid recipient", 400);
            }
            
            $destinatary = Array(
                "name" => request("client"),
                "document" => request("document"),
                "email" => request("email")
            );

            
            $amount = grava_money(request("amount"));
            $pixkey = request("pixkey");
            $pixkeyType = request("keytype");
            $description = request("description");

            $withdraw = Withdraw::createWithdrawal($user->id, $amount, $pixkey, $pixkeyType, $description, $destinatary);

            $success = Array(
                "success" => true,
                "message" => "Saque solicitado com sucesso!",
                "withdraw" => $this->getReturnWithdraw($withdraw)
            );
            
            
            return response()->json($success, $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    

    private function getReturnWithdraw($withdraw) {

        return Array(
            "reference" => $withdraw->reference,
            "total" => number_format(($withdraw->amount + $withdraw->charge), 2, ".", ""),
            "charge" => number_format($withdraw->charge, 2, ".", ""),
            "loquidAmount" => number_format(($withdraw->amount), 2, ".", ""),
            "status" => ($withdraw->status == 0 ? "PENDING" : ($withdraw->status == 1 ? "PAID" : "CANCELED")),
            "pixkey" => $withdraw->pix_key,
            "keytype" => $withdraw->pix_key_type,
            "destinataryName" => $withdraw->destinatary_name,
            "destinataryDocument" => $withdraw->destinatary_document,
            "destinataryEmail" => $withdraw->destinatary_email,
            "paidAt" => ($withdraw->paid_at instanceof \DateTime ? $withdraw->paid_at->format("Y-m-d H:i:s") : $withdraw->paid_at),
            "entoend" => $withdraw->pix_entoend,
            "errorMsg" => $withdraw->error_msg,
            "created_at" => ($withdraw->created_at instanceof \DateTime ? $withdraw->created_at->format("Y-m-d H:i:s") : $withdraw->created_at),
            "updated_at" => ($withdraw->updated_at instanceof \DateTime ? $withdraw->updated_at->format("Y-m-d H:i:s") : $withdraw->updated_at)
        );

    }


    public function getWithdraw($withdrawReference) {

        try{
            $user = \Illuminate\Support\Facades\Auth::user();
            $withdraw = Withdraw::where("reference", $withdrawReference)->where("user_id", $user->id)->first();

            if (!$withdraw) {
                throw new \Exception("Registro não encontrado", 404);
            }

            $success = Array(
                "success" => true,
                "withdraw" => $this->getReturnWithdraw($withdraw)
            );
            
            
            return response()->json($success, $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }


    public function listWithdrawals() {

        try {

            $start = request("start");
            $end = request("end");
            $status = request("status");
            $page = request("page");

            if (!is_numeric($page) || !($page > 0)) {
                $page = 1;
            }

            $user = \Illuminate\Support\Facades\Auth::user();
            $query = Withdraw::where("user_id", $user->id);

            if (strlen($start) == 10) {
                $a = explode("-", $start);
                if (!checkdate($a[1], $a[2], $a[0])) {
                    throw new \Exception("Data inicial inválida.", 400);
                }
                $query->where("created_at", ">=", "{$start} 00:00:00");
            }
            if (strlen($end) == 10) {
                $a = explode("-", $end);
                if (!checkdate($a[1], $a[2], $a[0])) {
                    throw new \Exception("Data final inválida.", 400);
                }
                $query->where("created_at", "<=", "{$end} 23:59:59");
            }

            switch($status) {
                case "PENDING": 
                    $query->where("status", 0);
                    break;
                case "PAID": 
                    $query->where("status", 1);
                    break;
                case "CANCELED": 
                    $query->where("status", 2);
                    break;
            }


            $withdrawals = $query->orderBy("created_at", "DESC")->offset((($page - 1) * 25))->limit(25)->get();

            $list = Array();
            foreach ($withdrawals as $withdraw) {
                $list[] = $this->getReturnWithdraw($withdraw);
            }

            $success = Array(
                "success" => true,
                "withdrawals" => $list
            );
            
            
            return response()->json($success, $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }

    
}