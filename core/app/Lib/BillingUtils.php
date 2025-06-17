<?php

namespace App\Lib;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Compliance;
use App\Models\Invoice;
use QrCode;
use Validator;

class BillingUtils {


    /**
     * Retorna o usuário autenticado ou se não tiver usuário autenticado busca pelo email se informado
     * 
     */
    public static function identifyAuthenticatedUser($email = null) {

        $user = null;
        if (Auth::guard('user')->user() !== null) {
            $userId = Auth::guard('user')->user()->id;
            $user = User::where("id", $userId)->first();
        } else if ($email != null){
            if (!empty($email)) {
                $user = User::where("email", $email)->first();
            }
        }

        return $user;
    }

    /**
     * Obtém o registro de compliance do cliente
     */
    public static function getUserCompliance($userId) {
        $compliance = null;
        if ($userId > 0) {
            $compliance = Compliance::where("user_id", $userId)->first();
        }
        return $compliance;
    }

    /**
     * Obtém o documento do cliente
     * 
     */
    public static function getUserDocument($userId, $compliance = null) {
        if ($compliance == null && $userId > 0) {
            $compliance = self::getUserCompliance($userId);
        }
        
        $document = null;

        if ($compliance != null && $userId > 0) {
            if (!empty($compliance->tax_id)) {
                $document = $compliance->tax_id;
            } else if (!empty($compliance->document)) {
                $document = $compliance->document;
            } else if (!empty($compliance->reg_no)) {
                $document = $compliance->reg_no;
            }
        }
        return $document;
    }

    /**
     * Monta a estrutura de um customer
     */
    public static function getCustomer($user, $request = null, $requestPerfix = null) {

        $compliance = null;
        $document = null;

        if ($user != null && $user->id > 0) {
            $compliance = self::getUserCompliance($user->id);
            $document = self::getUserDocument($user->id, $compliance);
        }

        $data = ($request != null ? $request->all() : null);

        $document = (empty($document) ?  self::getFromPost($request, "document", null, $requestPerfix) : $document);
        $customer = (object) Array(
            "name" => ($user != null ? "{$user->first_name} {$user->last_name}" : self::getFromPost($request, "name", null, $requestPerfix)),
            "document" => $document,
            "document_type" => (strlen($document) > 11 ? "CNPJ" : "CPF"),
            "zipcode" => ($compliance != null ? str_replace("-", "", $compliance->address_zipcode) : self::getFromPost($request, "zip", null, $requestPerfix)),
            "country_id" => 30,
            "state" => ($compliance != null ? $compliance->address_state : self::getFromPost($request, "state", null, $requestPerfix)),
            "city" => ($compliance != null ? $compliance->address_city : self::getFromPost($request, "city", null, $requestPerfix)),
            "district" => ($compliance != null ? $compliance->neighborhood : self::getFromPost($request, "neighborhood", null, $requestPerfix)),
            "street" => ($compliance != null ? $compliance->address : self::getFromPost($request, "address", null, $requestPerfix)),
            "number" => ($compliance != null ? $compliance->address_number : self::getFromPost($request, "number", null, $requestPerfix)),
            "email" => ($compliance != null ? $user->email : self::getFromPost($request, "email", null, $requestPerfix)),
            "phone" => ($compliance != null ? $compliance->phone : self::getFromPost($request, "telefone", null, $requestPerfix)),
            "mobilephone" => ($compliance != null ? $compliance->phone : self::getFromPost($request, "celular", null, $requestPerfix))
        );

        return $customer;
    }

    /**
     * Extrai um parametro de uma requisição POSt
     * 
     */
    private static function getFromPost($request, $key, $default, $prefix) {
        return ($request != null ? $request->post($prefix . "_" .$key, $default) : null);
    }


    public static function validateCardDataFromRequest($request) {

        $validator = Validator::make($request->all(), [
            'creditcard_holder' => 'required',
            'creditcard_document' => 'required',
            'creditcard_number' => 'required',
            'creditcard_cvv' => 'required|numeric',
            'creditcard_month' => 'required|numeric',
            'creditcard_year' => 'required|numeric',
            'creditcard_brand' => 'required',
            'creditcard_installments' => 'numeric'
        ]);
        
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }

        $installments = intval(request("creditcard_installments"));


        if (!($installments > 0)) {
            $installments = 1;
        }
        if ($installments > 24) {
            throw new \Exception("Invalid installments.");
        }
    }
    
    public static function getCardDataFromRequest($request) {


        $installments = intval(request("creditcard_installments"));
        $holder = request("creditcard_holder");
        $holderDocument = request("creditcard_document");
        $number = str_replace(" ", "", request("creditcard_number"));
        $cvv = request("creditcard_cvv");
        $month = request("creditcard_month");
        $year = request("creditcard_year");
        $brand = request("creditcard_brand");
        
        if (!($installments > 0)) {
            $installments = 1;
        }
        $cardData = Array(
            "holder" => $holder,
            "number" => $number,
            "cvv" => $cvv,
            "month" => $month,
            "year" => $year,
            "brand" => $brand,
            "installments" => $installments
        );

        return $cardData;
    }


    /**
     * 
     * Atualiza os dados de cobrança pix em uma invoice
     * 
     */
    public static function updateInvoicePixInfo($invoice, $user, $pixInfo) {
        $qrcode = null;
        if (!empty($pixInfo["qrcode"])) {
            if (substr($pixInfo["qrcode"], 0, 10) != "data:image") {
                $qrcode = "data:image/png;base64,". base64_encode(QrCode::format('png')->size(300)->generate($pixInfo["qrcode"]));
            } else {
                $qrcode = $pixInfo["qrcode"];
            }
        }
        
        
        Invoice::where("ref_id", $invoice->ref_id)->where("user_id", $user->id)
                ->update(Array(
                    "pix_transaction_id" => $pixInfo["txid"],
                    "pix_qrcode" => $qrcode,
                    "pix_copy_past" => $pixInfo["copy"]
                ));

        return $qrcode;
    }


    /**
     * 
     * Atualiza os dados de uma cobrança boleto em uma invoice
     * 
     */
    public static function updateInvoiceBoletoInfo($invoice, $user, $boletoInfo) {
        
        Invoice::where("ref_id", $invoice->ref_id)->where("user_id", $user->id)
                ->update(Array(
                    "boleto_transaction_id" => $boletoInfo["paymentId"],
                    "boleto_url" => $boletoInfo["boletoURL"],
                    "boleto_barcode" => $boletoInfo["barcode"],
                    "boleto_digitable_line" => $boletoInfo["digitableLine"]
                ));
    }

}