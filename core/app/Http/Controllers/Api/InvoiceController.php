<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Invoice;
use App\Models\Customer;

class InvoiceController extends Controller
{
    
    private $successStatus  = 200;
    
    public function invoice($invoiceId) {
        
        try {
            $invoice=Invoice::where("id", $invoiceId)->first();
            
            if ($invoice == null) {
                throw new \Exception("Invoice not found.", 404);
            }
            
            return response()->json(['invoice' => $this->invoiceReturn($invoice)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    public function ref($refId) {
        
        try {
            $invoice=Invoice::whereref_id($refId)->first();
            
            if ($invoice == null) {
                throw new \Exception("Invoice not found.", 404);
            }
            
            return response()->json(['invoice' => $this->invoiceReturn($invoice)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    public function queryInvoices(Request $request) {
        try {
        
            
            $validator = Validator::make($request->all(), [
                'start_date' => 'date',
                'end_date' => 'date'
            ]);
            
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
            
            $page = request("page");
            $limit = request("limit");
            
            $invoice_no = request("invoice_no");
            $startDate = request("start_date");
            $email = request("email");
            $endDate = request("end_date");
            
            $page = (is_numeric($page) && $page > 0 ? ($page - 1) : 0);
            $limit = (is_numeric($limit) && $limit > 0 ? ($limit < 50 ? $limit : 50) : 10);
            
            $user = \Illuminate\Support\Facades\Auth::user();
            $invoices = \App\Models\Invoice::where("user_id", $user->id)->where(function ($query) use ($invoice_no, $email, $startDate, $endDate) {
                if (!empty($invoice_no)) {
                    $query->orWhere("invoice_no", "LIKE", "%{$invoice_no}%");
                }
                if (!empty($email)) {
                    $query->orWhere("email", "LIKE", "%{$email}%");
                }
                if (!empty($startDate) && !empty($endDate)) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                
            })->orderBy('created_at', 'desc')->skip(($page * $limit))->take($limit)->get();
            
            $success = Array();
            
            foreach ($invoices as $invoice) {
                $success[] = $this->invoiceReturn($invoice);
            }
            
            return response()->json(['invoices' => $success, "pagination" => Array("page" => ($page+1), "limit" => $limit)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }
    
    
    private function invoiceReturn($invoice) {
        
        
        $data = Array(
            "id" => $invoice->id,
            "email" => $invoice->email,
            "invoice_no" => $invoice->invoice_no,
            "due_date" => $invoice->due_date,
            "tax" => number_format($invoice->tax, 2, ".", ""),
            "discount" => number_format($invoice->discount, 2, ".", ""),
            "quantity" => $invoice->quantity,
            "item" => $invoice->item,
            "notes" => $invoice->notes,
            "ref_id" => $invoice->ref_id,
            "amount" => number_format($invoice->amount, 2, ".", ""),
            "currency" => $invoice->currency,
            "charge" => number_format($invoice->charge, 2, ".", ""),
            "total" => number_format($invoice->total, 2, ".", ""),
            "status" => \App\Models\Invoice::getInvoiceStatus($invoice->status),
            "created_at" => substr($invoice->created_at, 0, 19),
            "updated_at" => substr($invoice->updated_at, 0, 19),
            "transactions" => Array()
        );
        
        if (!empty($invoice->boleto_transaction_id)) {
            $data["boleto"] = Array(
                "transaction" => $invoice->boleto_transaction_id,
                "link" => $invoice->boleto_url,
                "barcode" => $invoice->boleto_barcode,
                "digitable" => $invoice->boleto_digitable_line,
            );
        }
        
        if (!empty($invoice->pix_transaction_id)) {
            $data["pix"] = Array(
                "transaction" => $invoice->pix_transaction_id,
                "digitable" => $invoice->pix_copy_past,
                "qrcode" => $invoice->pix_qrcode
            );
        }
        
        
        if ($invoice->customer_id > 0) {
            $customer = \App\Models\Customer::where("id", $invoice->customer_id)->first();
            $st = \App\Models\State::where("uf", strtoupper($customer->state))->first();
            $country = \App\Models\Country::where("id", $customer->country_id)->first();
            $c = Array(
                "id" => $customer->id,
                "name" => $customer->name,
                "document" => $customer->document,
                "document_type" => $customer->document_type,
                "zipcode" => $customer->zipcode,
                "country" => Array(
                    "id" => $country->id,
                    "iso" => $country->iso,
                    "name" => $country->name,
                    "nicename" => $country->nicename,
                    "iso3" => $country->iso3,
                    "numcode" => $country->numcode,
                    "phonecode" => $country->phonecode,
                ),
                "state" => Array(
                    "name" => $st->name,
                    "uf" => $st->uf,
                    "ibge" => $st->ibge,
                    "ddd" => $st->ddd
                ),
                "city" => $customer->city,
                "district" => $customer->district,
                "street" => $customer->street,
                "number" => $customer->number,
                "email" => $customer->email,
                "phone" => $customer->phone,
                "mobilephone" => $customer->mobilephone,

            );
            
            $data["customer"] = $c;
        }
        
        $transactions = \App\Models\Transactions::where("invoice_id", $invoice->id)->get();
        foreach ($transactions as $transaction) {
            $t = Array(
                "id" => $transaction->id,
                "amount" => number_format($transaction->amount, 2, ".", ""),
                "currency" => $transaction->currency,
                "charge" => number_format($transaction->charge, 2, ".", ""),
                "type" => strtoupper($transaction->payment_type),
                "ref" => $transaction->ref_id,
                "status" => \App\Models\Transactions::getTransactionStatus($transaction->status),
                "created_at" => substr($transaction->created_at, 0, 19),
                "updated_at" => substr($transaction->updated_at, 0, 19)
            );
            
            $paymentData = Array();
            if ($transaction->payment_type == "pix") {
                $paymentData = Array(
                    "pix_end_to_end_id" => $transaction->pix_end_to_end_id,
                );
            } else if ($transaction->payment_type == "creditcard") {
                $paymentData = Array(
                    "brand" => $transaction->creditcard_brand,
                    "installments" => $transaction->creditcard_installments,
                    "status" => $transaction->creditcard_status,
                    "status_description" => $transaction->creditcard_status_description,
                    "transaction" => $transaction->creditcard_transaction_id,
                    "authorization" => $transaction->creditcard_authorization_code
                );
            } else if ($transaction->payment_type == "boleto") {
                $paymentData = Array(
                    "transaction" => $invoice->boleto_transaction_id,
                    "link" => $invoice->boleto_url,
                    "barcode" => $invoice->boleto_barcode,
                    "digitable" => $invoice->boleto_digitable_line,
                );
            }
            
            $t["payment"] = $paymentData;
            
            $data["transactions"][] = $t;
        }
        
        
        return $data;
    }
    
}
