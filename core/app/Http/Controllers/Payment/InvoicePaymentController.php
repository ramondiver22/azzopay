<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Settings;
use App\Models\Invoice;
use App\Models\User;
use App\Models\UserGateway;
use App\Models\Currency;
use App\Models\Transactions;
use App\Models\Charges;
use App\Models\History;
use App\Models\CreditcardFeeTable;
use App\Models\CreditcardFeeInstallment;

use App\Lib\Pix\PixGateway;
use App\Lib\Boleto\BoletoGateway;
use App\Lib\CreditCard\CreditCardGateway;
use App\Lib\BillingUtils;
use Session;

class InvoicePaymentController extends Controller {
    
    private $successStatus  = 200;

    public function processInvoice(Request $request) {
        try {

            $set=Settings::first();
            $inv=Invoice::whereRef_id($request->link)->first();
            
            if ($inv->status == 1) {
                throw new \Exception("A invoice já está paga.");
            }

            if ($inv->status == 2) {
                throw new \Exception("O status da invoice não permite essa operação.");
            }

            $currency=Currency::whereStatus(1)->first();
           
            $success = null;
            switch(strtolower($request->type)) {
                case "card" :
                    $success = $this->payInvoiceWithCard($request, $inv, $currency, $set);
                    break;
                case "account" :
                    $success = $this->payInvoiceWithAccount($request, $inv, $currency, $set);
                    break;
                case "pix" :
                    $success = $this->payInvoiceWithPix($request, $inv, $currency, $set);
                    break;
                case "boleto" :
                    $success = $this->payInvoiceWithBoleto($request, $inv, $currency, $set);
                    break;

                default:
                    throw new \Exception("Meio de pagamento inválido.");
            } 

            return response()->json($success, 200);
        } catch (\Exception $ex) {
            //exit($ex->getTraceAsString());
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        } 
    }
    

    
    private function payInvoiceWithAccount($request, $inv, $currency, $settings) {

        if (!UserGateway::validatePaymentMethod($inv->user_id, UserGateway::ACCOUNT)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        if (!Auth::guard('user')->check()) {
            $redirect = route('account.view.invoice', ['id' => $inv->ref_id]);
            Session::put('oldLink', $redirect);
            
            return redirect()->route('login');
        }

        $user=User::whereId(Auth::guard('user')->user()->id)->first();

        $inv->proccessPaymentWithAccountBalance($user);

        $success = Array(
            "success" => true,
            "message" => "Pagamento recebido com sucesso! Aguarde para ser redirecionado.",
            "redirect" => route('user.invoicelog'),
            "reference" => $inv->ref_id,
            "total" => $inv->total,
            "status" => Invoice::getInvoiceStatus($inv->status)
        );
        
        return $success;
    }

    private function payInvoiceWithCard(Request $request, $inv, $currency, $settings) {
        
        if (!UserGateway::validatePaymentMethod($inv->user_id, UserGateway::CREDIT_CARD)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        BillingUtils::validateCardDataFromRequest($request);

        $email = request("email");
        $user = BillingUtils::identifyAuthenticatedUser($email);
        
        $cardData = BillingUtils::getCardDataFromRequest($request);

        if ($user != null) {
            $inv->client_name =  $user->first_name . " " . $user->last_name;
            $inv->client_document = $cardData["holder"];
            $inv->save();
        }

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::CREDIT_CARD, $inv->user_id);
        $ICreditcard = CreditCardGateway::getGateway($gateway);

        $creditcardFeeTable = CreditcardFeeTable::getUserTable($inv->user_id);
        $amount = CreditcardFeeInstallment::calcInstalmentTotal($creditcardFeeTable, $cardData["installments"], $inv->total);
        
        $paymentInfo = $ICreditcard->createTransaction($amount, $inv->ref_id, $cardData);
        
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
        
        $paymentInfo->originalAmount = $inv->total;
        $inv->proccessPayment($paymentInfo, "creditcard", $user);

        if ($inv->status != 1) {
            throw new \Exception("Não foi possível processar o seu pagamento. Verifique as informações e tente novamente.");
        }

        $success = Array(
            "success" => true,
            "message" => "Pagamento recebido com sucesso! Aguarde para ser redirecionado.",
            "redirect" => route('card.view.invoice', ['id' => $inv->ref_id]),
            "reference" => $inv->ref_id,
            "total" => $inv->total,
            "status" => Invoice::getInvoiceStatus($inv->status),
            "transaction" => Array(
                "id" => (isset($paymentInfo->transactionId) ? $paymentInfo->transactionId : null),
                "amount" => (isset($paymentInfo->totalReceived) ? $paymentInfo->totalReceived : null),
                "status" => (isset($paymentInfo->statusEnum) ? $paymentInfo->statusEnum : null),
                "description" => (isset($paymentInfo->statusDescription) ? $paymentInfo->statusDescription : null),
                "method" => (isset($paymentInfo->paymentMethod) ? $paymentInfo->paymentMethod : null),
            )
        );
        
        return $success;
    }


    private function payInvoiceWithBoleto(Request $request, $inv, $currency, $settings) {

        if (!UserGateway::validatePaymentMethod($inv->user_id, UserGateway::BOLETO)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $userCustomer = BillingUtils::identifyAuthenticatedUser(null);
        
        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::BOLETO, $inv->user_id);
        $IBoleto = BoletoGateway::getGateway($gateway);

        $user = \App\Models\User::where("id", $inv->user_id)->first();
        $dueDate = new \DateTime($inv->due_date);

        $customer = BillingUtils::getCustomer($userCustomer, $request, "boleto");
        
        $info = $IBoleto->createTransaction($user, $inv->total, $inv->id, $inv->ref_id, $dueDate, $customer, null);

        BillingUtils::updateInvoiceBoletoInfo($inv, $user, $info);

        $success = Array(
            "success" => true,
            "message" => "Boleto gerado com sucesso!",
            "reference" => $inv->ref_id,
            "total" => $inv->total,
            "due_date" => $dueDate->format("d/m/Y H:i:s"),
            "status" => Invoice::getInvoiceStatus($inv->status),
            "boleto" => $info["boletoURL"],
            "barcode" => $info["barcode"],
            "digitable" => $info["digitableLine"]
        );

        return $success;
    }

    private function payInvoiceWithPix(Request $request, $inv, $currency, $settings) {
        
        if (!UserGateway::validatePaymentMethod($inv->user_id, UserGateway::PIX)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::PIX, $inv->user_id);
    
        $IPix = PixGateway::getGateway($gateway);
        
        $user = BillingUtils::identifyAuthenticatedUser(null);
        $document = null;
        if ($user != null) {
            $document = BillingUtils::getUserDocument($user->id);
        }

        
        if (!isset($request->pix_client_name) || !isset($request->pix_client_document) || empty($request->pix_client_name) || empty($request->pix_client_document)) {
            throw new \Exception("É necessário informar o seu nome e documento.");
        }

        $clientData = Array(
            "document" => $request->pix_client_document,
            "name" => $request->pix_client_name
        );
        
        $pix = $IPix->criarCobrancaQrCode($clientData, $inv->total, $gateway->val4, (!empty(request("notes")) ? request("notes") : "Invoice {$inv->ref_id}"));
        
        $merchant = User::where("id", $inv->user_id)->first();
        $qrcodeImage = BillingUtils::updateInvoicePixInfo($inv, $merchant, $pix);
        
        $success = Array(
            "success" => true,
            "reference" => $inv->ref_id,
            "total" => number_format($inv->total, 2, ",", "."),
            "txid" => $pix["txid"],
            "copy" => $pix["copy"],
            "qrcode" => $qrcodeImage
        );
        

        return $success;
    }


    public function verifyInvoice(Request $request) {
        try {
            $success = Array(
                "paid" => false
            );
            if (isset($request->reference) && !empty($request->reference)) {

                $invoice = Invoice::where("ref_id", $request->reference)->first();

                if ($invoice && $invoice->status == 1) {

                    $success = Array(
                        "paid" => true,
                        "redirect" => null,
                        "message" => "Pagamento registrado com sucesso!"
                    );
                } 
            }

            return response()->json($success, 200);
        } catch (\Exception $ex) {
            //exit($ex->getTraceAsString());
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        } 
    }

}