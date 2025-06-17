<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Invoice;
use App\Models\History;
use App\Models\UserTax;
use App\Models\UserGateway;
use App\Lib\Pix\PixGateway;
use App\Lib\BillingUtils;
use Validator;
use App\Lib\PixBancoBrasil;
use QrCode;


class PixController extends Controller {
    private $successStatus  = 200;
    
    public function create(Request $request) {
        
        try {
            
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'email' => 'required|email',
                'quantity' => 'required|integer',
                'discount' => 'required|numeric',
                'invoice_no' => 'required',
                'due_date' => 'required|date',
                'item_name' => 'required',
                'tax' => 'required|numeric',
                'document' => 'required',
                'client' => 'required'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if (!UserGateway::validatePaymentMethod($user->id, UserGateway::PIX)) {
                throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
            }
            if($user->email==request("email")){
                throw new \Exception("Invalid recipient", 400);
            }
            
            $token='INV-'.str_random(6);
            $set=Settings::first();
            
            $amount = request("amount");
            $quantity = request("quantity");
            $discount = request("discount");
            $tax = request("tax");
            
            $totalDiscount=  $amount*$quantity*$discount/100;
            $totalTax=       ($amount*$quantity*$tax/100);
            $totalInvoice = ( ($amount*$quantity) + $tax - $discount);

            $sav['user_id']=$user->id;
            $sav['ref_id']=$token;
            $sav['email']=request("email");
            $sav['invoice_no']=request("invoice_no");
            $sav['due_date']=request("due_date");
            $sav['tax']=$totalTax;
            $sav['discount']=$totalDiscount;
            $sav['quantity']=$quantity;
            $sav['item']=request("item_name");
            $sav['notes']=request("notes");
            $sav['amount']=$amount;
            $sav['total']= $totalInvoice;
            $sav['client_name']=request("client");
            $sav['client_document']=request("document");
            $invoice = Invoice::create($sav);

            $userTax = UserTax::getUserTax($user->id);
            
            $pixBancoBrasil = new PixBancoBrasil();
            
            $clientData = Array(
                "document" => request("document"),
                "name" => request("client"),
            );
            
            $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::PIX, $user->id);
            $IPix = PixGateway::getGateway($gateway);

            $pix = $IPix->criarCobrancaQrCode($clientData, $invoice->total, $gateway->val4, (!empty($invoice->notes) ? $invoice->notes : "Invoice {$invoice->ref_id}"));
            
            $qrcode = BillingUtils::updateInvoicePixInfo($invoice, $user, $pix);
            
            $success = Array(
                "reference" => $token,
                "total" => number_format($totalInvoice, 2, ".", ""),
                "txid" => $pix["txid"],
                "due_date" => $sav['due_date'],
                "copy" => $pix["copy"],
                "qrcode" => $qrcode
            );
            
            
            return response()->json(['invoice' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    public function invoice($refId) {
        
        try {
            $invoice=Invoice::whereref_id($refId)->first();
            
            if ($invoice == null) {
                throw new \Exception("Invoice not found.", 404);
            }
            
            unset($invoice->user_id);
            return response()->json(['invoice' => $invoice], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    
    public function txid($pixTxid) {
        
        try {
            $invoice=Invoice::where("pix_transaction_id", $pixTxid)->first();
            
            if ($invoice == null) {
                throw new \Exception("Invoice not found.", 404);
            }
            
            unset($invoice->user_id);
            return response()->json(['invoice' => $invoice], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    
    
}
