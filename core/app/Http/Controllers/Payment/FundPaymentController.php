<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Settings;
use App\Models\Deposits;
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

class FundPaymentController extends Controller {
    
    private $successStatus  = 200;

    public function processFund(Request $request) {
        try {

            $set=Settings::first();
            
            $user = BillingUtils::identifyAuthenticatedUser(null);
            if ($user == null) {
                throw new \Exception("Você precisa estar logado para executar essa operação.");
            }

            $currency=Currency::whereStatus(1)->first();

            if (!is_numeric($request->amount) || !($request->amount > 0)) {
                throw new \Exception("O valor da recarga precisa ser maior que zero.");
            }

            $success = null;
            switch(strtolower($request->type)) {
                case "creditcard" :
                    $success = $this->payFundWithCard($request, $currency, $set, $user);
                    break;
                case "pix" :
                    $success = $this->payFundWithPix($request, $currency, $set, $user);
                    break;
                case "boleto" :
                    $success = $this->payFundWithBoleto($request, $currency, $set, $user);
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
    
    private function payFundWithCard(Request $request, $currency, $settings, $user) {
        
        if (!UserGateway::validatePaymentMethod($user->id, UserGateway::CREDIT_CARD)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        BillingUtils::validateCardDataFromRequest($request);

        $cardData = BillingUtils::getCardDataFromRequest($request);

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::CREDIT_CARD, $user->id);
        $ICreditcard = CreditCardGateway::getGateway($gateway);

        $creditcardFeeTable = CreditcardFeeTable::getUserTable($user->id);
        $amount = CreditcardFeeInstallment::calcInstalmentTotal($creditcardFeeTable, $cardData["installments"], $request->amount);
        
        $charge= ($amount - $request->amount);
        $totalAmount = $amount;

        $deposit = Deposits::createDeposit($user->id, "creditcard", $gateway->id, $totalAmount, $charge, $currency);

        $paymentInfo = $ICreditcard->createTransaction($deposit->amount, $deposit->trx, $cardData);
        $paymentInfo->originalAmount = $request->amount;
        
        /*
        $paymentInfo = (Object) Array(
            "paymentMethod" => "Creditcard",
            "status" => 1,
            "statusEnum" => "AUTHORIZED",
            "statusDescription" => "Transacao concluida",
            "totalReceived" => 85.92,
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

        $paymentInfo->client_name = $request->creditcard_holder;
        $paymentInfo->client_document = $request->creditcard_document;

        $deposit->proccessPayment($paymentInfo, "creditcard");

        if ($deposit->status != 1) {
            throw new \Exception("Não foi possível processar o seu pagamento. Verifique as informações e tente novamente.");
        }
        
        $success = Array(
            "success" => true,
            "message" => "Pagamento recebido com sucesso! Aguarde para ser redirecionado.",
            "redirect" => route('user.dashboard'),
            "reference" => $deposit->trx,
            "total" => $deposit->amount,
            "status" => Deposits::getDepositStatus($deposit->status),
            "transaction" => Array(
                "id" => $deposit->creditcard_payment_id,
                "amount" => $deposit->amount,
                "status" => $deposit->creditcard_status,
                "description" => $deposit->creditcard_status_description,
                "method" => $deposit->payment_type,
            )
        );
        
        
        return $success;
    }


    private function payFundWithBoleto(Request $request, $currency, $settings, $user) {

        if (!UserGateway::validatePaymentMethod($user->id, UserGateway::BOLETO)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::BOLETO, $user->id);
        $IBoleto = BoletoGateway::getGateway($gateway);

        $charge= ($request->amount * $gateway->charge / 100);
        $totalAmount = ($request->amount + $charge);

        $dueDate = new \DateTime();
        $dueDate->add(new \DateInterval("P3D"));

        $customer = BillingUtils::getCustomer($user, $request, "boleto");
        
        $deposit = Deposits::createDeposit($user->id, "boleto", $gateway->id, $totalAmount, $charge, $currency);

        $info = $IBoleto->createTransaction($user, $deposit->amount, $deposit->id, $deposit->trx, $dueDate, $customer, null);

        $deposit->updateBoletoInfo($info["paymentId"], $info["boletoURL"], $info["barcode"], $info["digitableLine"]);

        $success = Array(
            "success" => true,
            "message" => "Boleto gerado com sucesso!",
            "reference" => $deposit->trx,
            "total" => number_format($deposit->amount, 2, ",", "."),
            "charge" => number_format($deposit->charge, 2, ",", "."),
            "recharge" => number_format(($deposit->amount - $deposit->charge), 2, ",", "."),
            "due_date" => $dueDate->format("d/m/Y H:i:s"),
            "status" => Deposits::getDepositStatus($deposit->status),
            "boleto" => $info["boletoURL"],
            "barcode" => $info["barcode"],
            "digitable" => $info["digitableLine"]
        );

        return $success;
    }

    private function payFundWithPix(Request $request, $currency, $settings, $user) {
        
        if (!UserGateway::validatePaymentMethod($user->id, UserGateway::PIX)) {
            throw new \Exception("A forma de pagamento selecionada não está habilitada para o comerciante!");
        }

        $gateway = \App\Models\UserGateway::getDefaultGateway(\App\Models\UserGateway::PIX, $user->id);
    
        $charge= ($request->amount * $gateway->charge / 100);
        $totalAmount = ($request->amount + $charge);

        $IPix = PixGateway::getGateway($gateway);
        
        if (!isset($request->pix_client_name) || !isset($request->pix_client_document) || empty($request->pix_client_name) || empty($request->pix_client_document)) {
            throw new \Exception("É ncessário informar o seu nome e documento.");
        }

        $clientData = Array(
            "document" => $request->pix_client_document,
            "name" => $request->pix_client_name
        );
        
        $deposit = Deposits::createDeposit($user->id, "pix", $gateway->id, $totalAmount, $charge, $currency);

        $pixInfo = $IPix->criarCobrancaQrCode($clientData, $deposit->amount, $gateway->val4, (!empty($request->notes) ? $request->notes : "Fund {$deposit->id}"));
        
        $qrcode = "data:image/png;base64,". base64_encode(QrCode::format('png')->size(300)->generate($pixInfo["qrcode"]));
        $deposit->updatePixInfo($pixInfo["txid"], $qrcode, $pixInfo["copy"]);

        $success = Array(
            "reference" => $deposit->trx,
            "total" => number_format($deposit->amount,  2, ",", "."),
            "txid" => $pixInfo["txid"],
            "copy" => $pixInfo["copy"],
            "qrcode" => $qrcode,
            "success" => true,
            "total" => number_format($deposit->amount,  2, ",", "."),
            "recharge" => number_format($deposit->amount - $deposit->charge, 2, ",", "."),
            "charge" => number_format($deposit->charge, 2, ",", ".")
        );
        

        return $success;
    
    }


    public function verifyFund(Request $request) {
        try {
            $success = Array(
                "paid" => false
            );
            if (isset($request->reference) && !empty($request->reference)) {

                $deposit = Deposits::where("trx", $request->reference)->first();

                if ($deposit && $deposit->status == 1) {
                    $success = Array(
                        "paid" => true,
                        "redirect" => route('user.dashboard'),
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