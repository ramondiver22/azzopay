<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\Cielo_lib;
use App\Models\Settings;
use App\Models\Invoice;
use App\Models\History;
use App\Models\User;
use App\Models\UserTax;
use App\Models\UserGateway;
use App\Models\Transactions;
use App\Models\Charges;
use App\Models\CreditcardFeeTable;
use App\Models\CreditcardFeeInstallment;
use App\Lib\CreditCard\CreditCardGateway;
use Validator;


class CreditcardController extends Controller {
    
    private $successStatus  = 200;
    
    public function create(Request $request) {
        
        try {
            
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'installments' => 'required|integer',
                'email' => 'required|email',
                'quantity' => 'required|integer',
                'discount' => 'required|numeric',
                'invoice_no' => 'required',
                'due_date' => 'required|date',
                'item_name' => 'required',
                'tax' => 'required|numeric',
                'creditcard_holder' => 'required',
                'creditcard_number' => 'required|numeric',
                'creditcard_cvv' => 'required|numeric',
                'creditcard_month' => 'required|numeric',
                'creditcard_year' => 'required|numeric',
                'creditcard_brand' => 'required'
            ]);
            
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
        
         
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if (!UserGateway::validatePaymentMethod($user->id, UserGateway::CREDIT_CARD)) {
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
            $installments = request("installments");
            
            
            $holder = request("creditcard_holder");
            $number = request("creditcard_number");
            $cvv = request("creditcard_cvv");
            $month = request("creditcard_month");
            $year = request("creditcard_year");
            $brand = request("creditcard_brand");
            
            if (!($installments > 0)) {
                throw new \Exception("Invalid installments.");
            }
            
            if ($installments > 24) {
                throw new \Exception("Invalid installments.");
            }
            
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
            $sav['client_name']=null;
            $sav['client_document']=null;
            
            $userTax = UserTax::getUserTax($user->id);
            //exit(print_r($sav));
            $invoice = Invoice::create($sav);
            
            $cardData = Array(
                "holder" => $holder,
                "number" => $number,
                "cvv" => $cvv,
                "month" => $month,
                "year" => $year,
                "brand" => $brand,
                "installments" => $installments
            );
            
            
            $gateway = UserGateway::getDefaultGateway(UserGateway::CREDIT_CARD, $invoice->user_id);
            $ICreditcard = CreditCardGateway::getGateway($gateway);
    
            $creditcardFeeTable = CreditcardFeeTable::getUserTable($invoice->user_id);
            $amount = CreditcardFeeInstallment::calcInstalmentTotal($creditcardFeeTable, $cardData["installments"], $invoice->total);
            
            $paymentInfo = $ICreditcard->createTransaction($amount, $invoice->ref_id, $cardData);
            print_r($paymentInfo);
            /*
            $paymentInfo = (Object) Array(
                "paymentMethod" => "Creditcard",
                "status" => 1,
                "statusEnum" => "AUTHORIZED",
                "statusDescription" => "Transacao concluida",
                "totalReceived" => number_format($amount, 2, ".", ""),
                "paymentId" => "123456",
                "brand" => "Visa",
                "json" => json_encode(Array("sucesso" => true, "teste" => true)),
                "installments" => 4,
                "authorizationCode" => "asdedrfcnhtyfgvicu",
                "transactionId" => "oidujjhrydtfgsnmmauwyeb",
                "proofOfSale" => "weyrdggvvc",
                "gateway" => "cielo"
            );
            */
            
            
            $paymentInfo->originalAmount = $invoice->total;
            $invoice->proccessPayment($paymentInfo, "creditcard", $user);
    
            if ($invoice->status != 1) {
                throw new \Exception("Não foi possível processar o seu pagamento. Verifique as informações e tente novamente.");
            }
            
           
            $success = Array(
                "reference" => $invoice->ref_id,
                "total" => number_format($invoice->total, 2, ".", ""),
                "due_date" => substr($invoice->duedate, 0, 10),
                "status" => Invoice::getInvoiceStatus($invoice->status),
                "transaction" => Array(
                    "id" => $paymentInfo->paymentId,
                    "amount" => $paymentInfo->totalReceived,
                    "status" => $paymentInfo->status,
                    "description" => $paymentInfo->statusDescription,
                    "method" =>  "creditcard",
                )
            );
            
            return response()->json(['invoice' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
}
