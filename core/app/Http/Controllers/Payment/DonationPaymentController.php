<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Settings;
use App\Models\Donation;
use App\Models\Paymentlink;
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
use QrCode;

class DonationPaymentController extends Controller {
    
    private $successStatus  = 200;

    public function processDonation(Request $request) {
        try {

            $set=Settings::first();
            $link = Paymentlink::whereref_id($request->link)->first();
            
            if ($link->status == 1) {
                throw new \Exception("O Link já está pago.");
            }

            if ($link->status == 2) {
                throw new \Exception("O status da Link não permite essa operação.");
            }

            $currency=Currency::whereStatus(1)->first();
           
            if (!is_numeric($request->status)) {
                throw new \Exception("Você precisa informar um perfil de anonimato.");
            }

            $success = null;
            switch(strtolower($request->type)) {
                case "card" :
                    $success = $this->payDonationWithCard($request, $link, $currency, $set);
                    break;
                case "account" :
                    $success = $this->payDonationWithAccount($request, $link, $currency, $set);
                    break;
                case "pix" :
                    $success = $this->payDonationWithPix($request, $link, $currency, $set);
                    break;
                case "boleto" :
                    $success = $this->payDonationWithBoleto($request, $link, $currency, $set);
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
    
    private function payDonationWithAccount($request, $link, $currency, $settings) {

        if (!UserGateway::validatePaymentMethod($link->user_id, UserGateway::ACCOUNT)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        if (!Auth::guard('user')->check()) {
            $redirect = route('account.dpview.link', ['id' => $link->ref_id]);
            Session::put('oldLink', $redirect);
            
            return redirect()->route('login');
        }

        if (!($request->amount > 0)) {
            throw new \Exception("O valor doado precisa ser maior que zero!");
        }

        $user = User::whereId(Auth::guard('user')->user()->id)->first();

        $link->proccessPaymentWithAccountBalance($user, $request->amount, $request->status);

        $redirect = route('user.transactionsd');
        if($link->redirect_link!=null){
            $redirect = $link->redirect_link;
        }

        $success = Array(
            "success" => true,
            "message" => "Doação recebida com sucesso! Aguarde para ser redirecionado.",
            "redirect" => $redirect,
            "reference" => $link->ref_id,
            "total" => number_format($request->amount, 2, ",", ".")
        );
        
        return $success;
    }

    private function payDonationWithCard(Request $request, $link, $currency, $settings) {
        
        if (!UserGateway::validatePaymentMethod($link->user_id, UserGateway::CREDIT_CARD)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        if (!($request->amount > 0)) {
            throw new \Exception("O valor doado precisa ser maior que zero!");
        }

        BillingUtils::validateCardDataFromRequest($request);

        $email = request("email");
        $user = BillingUtils::identifyAuthenticatedUser($email);
        
        $cardData = BillingUtils::getCardDataFromRequest($request);

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::CREDIT_CARD, $link->user_id);
        $ICreditcard = CreditCardGateway::getGateway($gateway);

        
        $creditcardFeeTable = CreditcardFeeTable::getUserTable($link->user_id);
        $amount = CreditcardFeeInstallment::calcInstalmentTotal($creditcardFeeTable, $cardData["installments"], $request->amount);
        
        $paymentInfo = $ICreditcard->createTransaction($amount, $link->ref_id, $cardData);
        
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
        
        $paymentInfo->originalAmount = $request->amount;
        $link->proccessPayment($paymentInfo, "creditcard", $user, $request->status);

        $redirect = route('card.dpview.link', ['id' => $link->ref_id]);
        if($link->redirect_link!=null){
            $redirect = $link->redirect_link;
        }

        $success = Array(
            "success" => true,
            "message" => "Doação recebida com sucesso! Aguarde para ser redirecionado.",
            "redirect" => $redirect,
            "reference" => $link->ref_id,
            "total" => number_format($request->amount, 2, ",", "."),
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


    private function payDonationWithBoleto(Request $request, $link, $currency, $settings) {

        if (!UserGateway::validatePaymentMethod($link->user_id, UserGateway::BOLETO)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        if (!($request->amount > 0)) {
            throw new \Exception("O valor doado precisa ser maior que zero!");
        }

        $userCustomer = BillingUtils::identifyAuthenticatedUser(null);
        
        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::BOLETO, $link->user_id);
        $IBoleto = BoletoGateway::getGateway($gateway);

        $user = \App\Models\User::where("id", $link->user_id)->first();
        $dueDate = new \DateTime(date("Y-m-d H:i:s"));
        $dueDate->add(new \DateInterval("P3D"));

        $customer = BillingUtils::getCustomer($userCustomer, $request, "boleto");
        
        $info = $IBoleto->createTransaction($user, $request->amount, $link->id, $link->ref_id, $dueDate, $customer, null);

        $reg = $link->updateBoletoInfo($info["paymentId"], $info["boletoURL"], $info["barcode"], $info["digitableLine"], $request->amount, $request->status, $link->user_id);

        $success = Array(
            "success" => true,
            "message" => "Boleto gerado com sucesso!",
            "reference" => $reg->ref_id,
            "total" => number_format($request->amount, 2, ",", "."),
            "due_date" => $dueDate->format("d/m/Y H:i:s"),
            "boleto" => $info["boletoURL"],
            "barcode" => $info["barcode"],
            "digitable" => $info["digitableLine"]
        );

        return $success;
    }

    private function payDonationWithPix(Request $request, $link, $currency, $settings) {
        
        if (!UserGateway::validatePaymentMethod($link->user_id, UserGateway::PIX)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        if (!($request->amount > 0)) {
            throw new \Exception("O valor doado precisa ser maior que zero!");
        }

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::PIX, $link->user_id);
    
        $IPix = PixGateway::getGateway($gateway);
        
        $user = BillingUtils::identifyAuthenticatedUser(null);
        $document = null;
        
        if (!isset($request->pix_client_name) || !isset($request->pix_client_document) || empty($request->pix_client_name) || empty($request->pix_client_document)) {
            throw new \Exception("É ncessário informar o seu nome e documento.");
        }

        $clientData = Array(
            "document" => $request->pix_client_document,
            "name" => $request->pix_client_name
        );
        
        $pix = $IPix->criarCobrancaQrCode($clientData, $request->amount, $gateway->val4, (!empty(request("notes")) ? request("notes") : "Invoice {$link->ref_id}"));
        
        $qrcode = "data:image/png;base64,". base64_encode(QrCode::format('png')->size(300)->generate($pix["qrcode"]));
        $reg = $link->updatePixInfo($pix["txid"], $qrcode, $pix["copy"], $request->amount, $request->status, $link->user_id);

        $success = Array(
            "success" => true,
            "reference" => $reg->ref_id,
            "total" => number_format($request->amount, 2, ",", "."),
            "txid" => $pix["txid"],
            "copy" => $pix["copy"],
            "qrcode" => $qrcode,
        );
        

        return $success;
    }

    public function verifyDonation(Request $request) {
        try {
            $success = Array(
                "paid" => false
            );
            if (isset($request->reference) && !empty($request->reference)) {

                $donation = Donations::where("ref_id", $request->reference)->first();

                if ($donation && $donation->status == 1) {
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