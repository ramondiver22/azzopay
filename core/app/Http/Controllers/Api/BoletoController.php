<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Settings;
use App\Models\Invoice;
use App\Models\History;
use App\Models\UserTax;
use App\Models\UserGateway;

class BoletoController extends Controller {
   private $successStatus  = 200;
    
    public function create(Request $request) {
        
        try {
            
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'quantity' => 'required|integer',
                'discount' => 'required|numeric',
                'invoice_no' => 'required|string',
                'due_date' => 'required|date',
                'item_name' => 'required|string',
                'customer' => 'required|integer|min:1'
            ]);
            
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
            
            $user = \Illuminate\Support\Facades\Auth::user();


            if (!UserGateway::validatePaymentMethod($user->id, UserGateway::BOLETO)) {
                throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
            }
            
            $token='INV-'.str_random(6);
            $set=Settings::first();
            
            $amount = request("amount");
            $quantity = request("quantity");
            $discount = request("discount");
            $customerId = request("customer");
            $instructions = request("instructions");
            
            $customer = \App\Models\Customer::where("id", $customerId)->where("user_id", $user->id)->first();
            
            if (!$customer) {
                throw new \Exception("You must to send the customer data.", 400);
            }
            
            $dueDate = new \DateTime(request("due_date") . " 23:59:59");
            $today = new \DateTime(date("Y-m-d H:i:s"));
            $maxDate = new \DateTime(date("Y-m-d H:i:s"));
            $maxDate->add(new \DateInterval("P15Y"));
            
            if ($dueDate->getTimestamp() < $today->getTimestamp()) {
                throw new \Exception("Invalid due date.", 400);
            }
            
            
            if ($dueDate->getTimestamp() > $maxDate->getTimestamp()) {
                throw new \Exception("Invalid due date.", 400);
            }
            
            $tax = request("tax");
            
            $totalDiscount=  $amount*$quantity*$discount/100;
            $totalTax=       ($amount*$quantity*$tax/100);
            $totalInvoice = ( ($amount*$quantity) + $tax - $discount);
            $sav['user_id']=$user->id;
            $sav['ref_id']=$token;
            $sav['customer_id']=$customer->id;
            $sav['email']=$customer->email;
            $sav['invoice_no']=request("invoice_no");
            $sav['due_date']=$dueDate->format("Y-m-d");
            $sav['tax']=$totalTax;
            $sav['discount']=$totalDiscount;
            $sav['quantity']=$quantity;
            $sav['item']=request("item_name");
            $sav['notes']=request("notes");
            $sav['amount']=$amount;
            $sav['total']= $totalInvoice;
            $sav['client_name']=null;
            $sav['client_document']=null;
            

            $userTax = UserTax::getUserTax($user->id);

            //exit(print_r($sav));
            $invoice = Invoice::create($sav);
            
            $his['user_id']=$user->id;
            $his['amount']=$tax-$discount-(($tax-$discount)*$userTax->invoice_charge/100);
            $his['ref']=$token;
            $his['main']=1;
            $his['type']=1;
            History::create($his);
            
            $cielo = new \App\Lib\Cielo_lib();
            $invoice = $cielo->createBoletoTransaction($invoice, $customer, $instructions);
            
            $success = Array(
                "reference" => $invoice->ref_id,
                "total" => $invoice->total,
                "due_date" => $invoice->due_date,
                "status" => Invoice::getInvoiceStatus($invoice->status),
                "boleto" => $invoice->boleto_url,
                "barcode" => $invoice->boleto_barcode,
                "digitable" => $invoice->boleto_digitable_line
            );
            
            return response()->json(['invoice' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            //echo $ex->getTraceAsString();
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
}
