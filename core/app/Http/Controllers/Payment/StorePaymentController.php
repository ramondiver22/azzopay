<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Settings;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserGateway;
use App\Models\Currency;
use App\Models\Transactions;
use App\Models\Charges;
use App\Models\History;
use App\Models\Storefront;
use App\Models\CreditcardFeeTable;
use App\Models\CreditcardFeeInstallment;

use App\Lib\Pix\PixGateway;
use App\Lib\Boleto\BoletoGateway;
use App\Lib\CreditCard\CreditCardGateway;
use App\Lib\BillingUtils;
use Session;
use QrCode;

class StorePaymentController extends Controller {
    
    private $successStatus  = 200;

    public function proccessOrder(Request $request) {
        try {
            Session::put('amount', $request->amount);
            Session::put('shipping_fee', $request->shipping_fee);
            Session::put('shipping', $request->shipping);
            Session::put('first_name', $request->first_name);
            Session::put('last_name', $request->last_name);
            Session::put('email', $request->email);
            Session::put('phone', $request->phone);
            Session::put('address', $request->address);
            Session::put('country', $request->country);
            Session::put('state', $request->state);
            Session::put('town', $request->town);
            Session::put('note', $request->note);
            Session::put('xship', $request->xship);

            $set=Settings::first();
            $cart=Cart::whereuniqueid($request->product_id)->get();
            
            if (! ( sizeof($cart) > 0 )) {
                throw new \Exception("Carrinho de compras inválido. Não encontrado.");
            }

            $currency=Currency::whereStatus(1)->first();
           
            $success = null;
            switch(strtolower($request->type)) {
                case "card" :
                    $success = $this->payOrderWithCard($request, $cart, $currency, $set);
                    break;
                case "account" :
                    $success = $this->payOrderWithAccount($request, $cart, $currency, $set);
                    break;
                case "pix" :
                    $success = $this->payOrderWithPix($request, $cart, $currency, $set);
                    break;
                case "boleto" :
                    $success = $this->payOrderWithBoleto($request, $cart, $currency, $set);
                    break;

                default:
                    throw new \Exception("Meio de pagamento inválido.");
            } 

            Session::forget('uniqueid');
            return response()->json($success, 200);
        } catch (\Exception $ex) {
            //exit($ex->getTraceAsString());
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        } 
    }
    
    private function getShippingData($request) {
        if($request->has('xship')){
            if (!empty($request->shipping)) {
                $dd = explode("-", $request->shipping);
                $df = trim($dd[1]);
            } else {
                $df = null;
            }
            
            return (object) Array(
                "shipping_fee" => $df,
                "ship_id" => $request->xship
            );
        } else {
            return (object) Array(
                "shipping_fee" => null,
                "ship_id" => null
            );
        }
    }


    private function getAddressData($request) {
        return (object) Array(
            "address" => (isset($request->address) ? $request->address : null),
            "country" => (isset($request->country) ? $request->country : null),
            "state" => (isset($request->state) ? $request->state : null),
            "town" => (isset($request->town) ? $request->town : null),
            "note" => (isset($request->note) ? $request->note : null)
        );
    }

    private function getClientData($request) {
        
        $user = BillingUtils::identifyAuthenticatedUser(null);
        if ($user == null) {
            $user = (object) Array(
                "id" => null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone
            );
        }

        return $user;
    }

    private function payOrderWithAccount($request, $cart, $currency, $settings) {
        


        $cartf=Cart::whereuniqueid($request->product_id)->first();
        if (!Auth::guard('user')->check()) {
            $redirect = route('user.scardpay', ['id' => $cartf->uniqueid]);
            Session::put('oldLink', $redirect);
            
            return redirect()->route('login');
        }

        $user=User::whereId(Auth::guard('user')->user()->id)->first();

        $merchant=Storefront::whereid($cartf->store)->first();

        if (!UserGateway::validatePaymentMethod($merchant->user_id, UserGateway::ACCOUNT)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $seller = User::whereId($merchant->user_id)->first();

        $orders = Order::saveOrderList($user, $seller, $cart, $this->getShippingData($request), $this->getAddressData($request));

        $totalValue = $request->amount + ($request->shipping_fee * $request->qtdProducts);
        $orders[0]->proccessPaymentWithAccountBalance($user, $totalValue);

        $redirect = null;

        $success = Array(
            "success" => true,
            "message" => "Pagamento recebido com sucesso!",
            "redirect" => $redirect,
            "reference" => $orders[0]->ref_id,
            "total" => number_format($totalValue, 2, ",", ".")
        );
        
        return $success;
    }

    private function payOrderWithCard(Request $request, $cart, $currency, $settings) {
        
        BillingUtils::validateCardDataFromRequest($request);

        $cardData = BillingUtils::getCardDataFromRequest($request);
        $user = $this->getClientData($request);

        $cartf=Cart::whereuniqueid($request->product_id)->first();
        $merchant=Storefront::whereid($cartf->store)->first();

        if (!UserGateway::validatePaymentMethod($merchant->user_id, UserGateway::CREDIT_CARD)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }
        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::CREDIT_CARD, $merchant->user_id);
        $ICreditcard = CreditCardGateway::getGateway($gateway);

        $seller = User::whereId($merchant->user_id)->first();

        
        $orders = Order::saveOrderList($user, $seller, $cart, $this->getShippingData($request), $this->getAddressData($request));

        $totalValue = 0;
        foreach($orders as $order) {
            $totalValue += $order->total;
        }

        $creditcardFeeTable = CreditcardFeeTable::getUserTable($merchant->user_id);
        $amount = CreditcardFeeInstallment::calcInstalmentTotal($creditcardFeeTable, $cardData["installments"], $totalValue);

        $paymentInfo = $ICreditcard->createTransaction($amount, $orders[0]->ref_id, $cardData);
        

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
        
        $paymentInfo->originalAmount = $totalValue;
        $orders[0]->proccessPayment($paymentInfo, "creditcard", $user);

        $redirect = null;

        $success = Array(
            "success" => true,
            "message" => "Pagamento recebido com sucesso!",
            "redirect" => $redirect,
            "reference" => $orders[0]->ref_id,
            "total" => number_format($totalValue, 2, ",", "."),
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


    private function payOrderWithBoleto(Request $request, $cart, $currency, $settings) {


        $cartf=Cart::whereuniqueid($request->product_id)->first();
        $merchant=Storefront::whereid($cartf->store)->first();

        if (!UserGateway::validatePaymentMethod($merchant->user_id, UserGateway::BOLETO)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $userCustomer = $this->getClientData($request);
        
        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::BOLETO, $merchant->user_id);
        $IBoleto = BoletoGateway::getGateway($gateway);

        $dueDate = new \DateTime(date("Y-m-d H:i:s"));
        $dueDate->add(new \DateInterval("P3D"));

        $customer = BillingUtils::getCustomer($userCustomer, $request, "boleto");
        
        $totalValue = $request->amount + ($request->shipping_fee * $request->qtdProducts);

        $seller = User::whereId($merchant->user_id)->first();

        $orders = Order::saveOrderList($userCustomer, $seller, $cart, $this->getShippingData($request), $this->getAddressData($request));
       
        $info = $IBoleto->createTransaction($seller, $totalValue, $cartf->id, $orders[0]->ref_id, $dueDate, $customer, null);

        $orders[0]->updateBoletoInfo($info["paymentId"], $info["boletoURL"], $info["barcode"], $info["digitableLine"]);

        $success = Array(
            "success" => true,
            "message" => "Boleto gerado com sucesso!",
            "reference" => $orders[0]->ref_id,
            "total" => number_format($totalValue, 2, ",", "."),
            "due_date" => $dueDate->format("d/m/Y H:i:s"),
            "boleto" => $info["boletoURL"],
            "barcode" => $info["barcode"],
            "digitable" => $info["digitableLine"]
        );

        return $success;
    }

    private function payOrderWithPix(Request $request, $cart, $currency, $settings) {

        $cartf=Cart::whereuniqueid($request->product_id)->first();
        $merchant=Storefront::whereid($cartf->store)->first();

        if (!UserGateway::validatePaymentMethod($merchant->user_id, UserGateway::PIX)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }
        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::PIX, $merchant->user_id);
    
        $IPix = PixGateway::getGateway($gateway);
        
        $user = $this->getClientData($request);
        $document = null;
        if ($user != null) {
            $document = BillingUtils::getUserDocument($user->id);
        }

        
        if (!isset($request->pix_client_name) || !isset($request->pix_client_document) || empty($request->pix_client_name) || empty($request->pix_client_document)) {
            throw new \Exception("É ncessário informar o seu nome e documento.");
        }

        $clientData = Array(
            "document" => $request->pix_client_document,
            "name" => $request->pix_client_name
        );
        
        $totalValue = $request->amount + ($request->shipping_fee * $request->qtdProducts);

        $seller = User::whereId($merchant->user_id)->first();
        $orders = Order::saveOrderList($user, $seller, $cart, $this->getShippingData($request), $this->getAddressData($request));

        $notes = (isset($request->notes) ? $request->notes : null);
        $pix = $IPix->criarCobrancaQrCode($clientData, $totalValue, $gateway->val4, (empty($notes) ? "Invoice {$orders[0]->ref_id}" : $notes ));
        
        $qrcode = "data:image/png;base64,". base64_encode(QrCode::format('png')->size(300)->generate($pix["qrcode"]));
        $orders[0]->updatePixInfo($pix["txid"], $qrcode, $pix["copy"]);

        $success = Array(
            "success" => true,
            "reference" => $orders[0]->ref_id,
            "total" => number_format($totalValue, 2, ",", "."),
            "txid" => $pix["txid"],
            "copy" => $pix["copy"],
            "qrcode" => $qrcode
        );
        

        return $success;
    }



    public function verifyOrder(Request $request) {
        try {
            $success = Array(
                "paid" => false
            );
            if (isset($request->reference) && !empty($request->reference)) {

                $order = Order::where("ref_id", $request->reference)->first();

                if ($order && $order->status == 1) {

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