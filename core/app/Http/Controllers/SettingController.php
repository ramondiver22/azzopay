<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Admin;
use App\Models\Etemplate;
use App\Models\CreditcardFeeInstallment;
use App\Models\CreditcardFeeTable;
use App\Models\UserGateway;
use App\Models\Gateway;
use Carbon\Carbon;
 
 
class SettingController extends Controller {
    

    public function Settings() {
        $data['lang'] = parent::getLanguageVars("admin_settings_page");
         
        $data['gatewaysIn'] = Gateway::where("status", 1)->where("sandbox", 0)->where("cashin", 1)->get();
        $data['gatewaysOut'] = Gateway::where("status", 1)->where("sandbox", 0)->where("cashout", 1)->get();
        $data['gatewaysBoleto'] = Gateway::where("status", 1)->where("sandbox", 0)->where("boleto", 1)->get();
        $data['gatewaysCreditcard'] = Gateway::where("status", 1)->where("sandbox", 0)->where("creditcard", 1)->get();

        $userGatewayCashIn = UserGateway::where("type", UserGateway::PIX)->whereNull('user_id')->first();
        $userGatewayCashOut = UserGateway::where("type", UserGateway::PIX_OUT)->whereNull('user_id')->first();
        $userGatewayBoleto = UserGateway::where("type", UserGateway::BOLETO)->whereNull('user_id')->first();
        $userGatewayCreditcard = UserGateway::where("type", UserGateway::CREDIT_CARD)->whereNull('user_id')->first();

        $data['gatewayCashIn'] = $userGatewayCashIn;
        $data['gatewayCashOut'] = $userGatewayCashOut;
        $data['gatewayBoleto'] = $userGatewayBoleto;
        $data['gatewayCreditcard'] = $userGatewayCreditcard;
        
        $data['title']='General settings';
        $data['val']=Admin::first();
        return view('admin.settings.index', $data);
    }     
    
    public function Email() {
        $data['lang'] = parent::getLanguageVars("admin_settings_page");
        $data['title']='Email settings';
        $data['val']=Etemplate::first();
        return view('admin.settings.email', $data);
    } 

    public function EmailUpdate(Request $request) {
        $data = Etemplate::findOrFail(1);
        $data->esender=$request->sender;
        $data->emessage=Purifier::clean($request->message);
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }      

    public function SettlementUpdate(Request $request) {
        $data = Settings::findOrFail(1);
        $data->duration=  "2";
        $data->period = "Day"; 
        $data->withdraw_charge = grava_money($request->withdraw_charge, 2);
        $data->withdraw_chargep = grava_money($request->withdraw_chargep, 2);
        $data->withdraw_limit = grava_money($request->withdraw_limit, 2);            
        $data->starter_limit = grava_money($request->starter_limit, 2);  
        $data->business_limit = grava_money($request->business_limit, 2);   
        $data->max_automatic_withdraw_value = grava_money($request->max_automatic_withdraw_value, 2);   
        
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    } 

    public function updatePixGateways() {
        
        try {
            $gateway_cashin = request("gateway_cashin");
            $gateway_cashout = request("gateway_cashout");
            $gateway_boleto = request("gateway_boleto");
            $gateway_creditcard = request("gateway_creditcard");

            if (!is_numeric($gateway_cashin) || !($gateway_cashin >0)) {
                throw new \Exception("Você deve informar um gateway para cashin.");
            }
            if (!is_numeric($gateway_cashout) || !($gateway_cashout >0)) {
                throw new \Exception("Você deve informar um gateway para cashout.");
            }

            if (!is_numeric($gateway_boleto) || !($gateway_boleto >0)) {
                throw new \Exception("Você deve informar um gateway para boletos.");
            }

            if (!is_numeric($gateway_creditcard) || !($gateway_creditcard >0)) {
                throw new \Exception("Você deve informar um gateway para cartão de crédito.");
            }

            $gatewayCashIn = Gateway::where("id", $gateway_cashin)->first();
            $gatewayCashOut = Gateway::where("id", $gateway_cashout)->first();
            $gatewayCreditcard = Gateway::where("id", $gateway_creditcard)->first();
            $gatewayBoleto = Gateway::where("id", $gateway_boleto)->first();

            if (!$gatewayCashIn) {
                throw new \Exception("Gateway de cashin inválido.");
            }
             
            if (!$gatewayCashOut) {
                throw new \Exception("Gateway de cashout inválido.");
            }
            if (!$gatewayCreditcard) {
                throw new \Exception("Gateway de de cartão de crédito inválido.");
            }
            if (!$gatewayBoleto) {
                throw new \Exception("Gateway de boleto inválido.");
            }

            $userGatewayCashIn = UserGateway::where("type", UserGateway::PIX)->whereNull('user_id')->first();
            $userGatewayCashOut = UserGateway::where("type", UserGateway::PIX_OUT)->whereNull('user_id')->first();
            $userGatewayBoleto = UserGateway::where("type", UserGateway::BOLETO)->whereNull('user_id')->first();
            $userGatewayCreditcard = UserGateway::where("type", UserGateway::CREDIT_CARD)->whereNull('user_id')->first();

            $userGatewayCashIn->gateway_id = $gatewayCashIn->id;
            $userGatewayCashOut->gateway_id = $gatewayCashOut->id;
            $userGatewayCreditcard->gateway_id = $gatewayCreditcard->id;
            $userGatewayBoleto->gateway_id = $gatewayBoleto->id;

            $userGatewayCashIn->save();
            $userGatewayCashOut->save();
            $userGatewayCreditcard->save();
            $userGatewayBoleto->save();

            return response()->json(
                ['message' => 'Gateways Atualizados com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }

    public function comissions(Request $request) {

        try {

            $data = Settings::findOrFail(1);
            $data->withdraw_comission = grava_money($request->withdraw_comission, 2);
            $data->deposit_comission = grava_money($request->deposit_comission, 2); 
            $data->invoice_comission = grava_money($request->invoice_comission, 2);
            $data->payment_link_comission = grava_money($request->payment_link_comission, 2);
            $data->donation_comission = grava_money($request->donation_comission, 2);            
            $data->store_comission = grava_money($request->store_comission, 2);   

            $res = $data->save();
            if (!$res) {
                throw new \Exception("Não foi possível atualizar os dados. Verifique as informações e tente novamente.");
            }

            return response()->json(
                ['message' => 'Comissões Atualizadas com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    } 
    
    public function Account() {
        $data['lang'] = parent::getLanguageVars("admin_settings_page");
        $data['title']='Change account details';
        $data['val']=Admin::first();
        return view('admin.settings.account', $data);
    } 

    public function AccountUpdate(Request $request) {
        $data = Admin::whereid(1)->first();
        $data->username=$request->username;
        $data->password=Hash::make($request->password);
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }  
        
    
    public function SettingsUpdate(Request $request) {
        $data = Settings::findOrFail(1);
        $data->site_name=$request->site_name;
        $data->livechat=$request->livechat;
        $data->email=$request->email;
        $data->support_email=$request->support_email;
        $data->mobile=$request->mobile;
        $data->title=$request->title;
        $data->withdraw_duration=$request->withdraw_duration;
        $data->site_desc=$request->site_desc;
        $data->welcome_message=$request->welcome_message;
        
        
        $data->company_document=$request->company_document;
        $data->company_address=$request->company_address;
        $data->company_name=$request->company_name;
        
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    

    public function Features(Request $request) {
        $data = Settings::findOrFail(1);  
        if(empty($request->email_activation)){
            $data->email_verification=0;	
        }else{
            $data->email_verification=$request->email_activation;
        }             
        if(empty($request->email_notify)){
            $data->email_notify=0;	
        }else{
            $data->email_notify=$request->email_notify;
        }      
        if(empty($request->registration)){
            $data->registration=0;	
        }else{
            $data->registration=$request->registration;
        }                   
        if(empty($request->merchant)){
            $data->merchant=0;	
        }else{
            $data->merchant=$request->merchant;
        }         
        if(empty($request->recaptcha)){
            $data->recaptcha=0;	
        }else{
            $data->recaptcha=$request->recaptcha;
        }           
        if(empty($request->subscription)){
            $data->subscription=0;	
        }else{
            $data->subscription=$request->subscription;
        }           
        if(empty($request->transfer)){
            $data->transfer=0;	
        }else{
            $data->transfer=$request->transfer;
        }          
        if(empty($request->request_money)){
            $data->request_money=0;	
        }else{
            $data->request_money=$request->request_money;
        }           
        if(empty($request->invoice)){
            $data->invoice=0;	
        }else{
            $data->invoice=$request->invoice;
        }          
        if(empty($request->store)){
            $data->store=0;	
        }else{
            $data->store=$request->store;
        }           
        if(empty($request->donation)){
            $data->donation=0;	
        }else{
            $data->donation=$request->donation;
        }           
        if(empty($request->single)){
            $data->single=0;	
        }else{
            $data->single=$request->single;
        }        
        if(empty($request->bill)){
            $data->bill=0;	
        }else{
            $data->bill=$request->bill;
        }        
        if(empty($request->vcard)){
            $data->vcard=0;	
        }else{
            $data->vcard=$request->vcard;
        } 
        /*           
        if(empty($request->bitcoin)){
            $data->bitcoin=0;	
        }else{
            $data->bitcoin=$request->bitcoin;
        }        
        if(empty($request->ethereum)){
            $data->ethereum=0;	
        }else{
            $data->ethereum=$request->ethereum;
        }  
        */       
        if(empty($request->stripe_connect)){
            $data->stripe_connect=0;	
        }else{
            $data->stripe_connect=$request->stripe_connect;
        }        
        if(empty($request->kyc_restriction)){
            $data->kyc_restriction=0;	
        }else{
            $data->kyc_restriction=$request->kyc_restriction;
        }         
        if(empty($request->country_restriction)){
            $data->country_restriction=0;	
        }else{
            $data->country_restriction=$request->country_restriction;
        }    
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }      
    
    public function charges(Request $request) {
        $data = Settings::findOrFail(1);
        $data->transfer_charge=$request->transfer_charge;
        $data->transfer_chargep=$request->transfer_chargep;
        $data->balance_reg=$request->bal;
        $data->withdraw_duration=$request->withdraw_duration;
        $data->merchant_charge=$request->merchant_charge;
        $data->merchant_chargep=$request->merchant_chargep;
        $data->invoice_charge=$request->invoice_charge;
        $data->invoice_chargep=$request->invoice_chargep;
        $data->product_charge=$request->product_charge; 
        $data->product_chargep=$request->product_chargep; 
        $data->subscription_charge=$request->subscription_charge; 
        $data->subscription_chargep=$request->subscription_chargep; 
        $data->donation_charge=$request->donation_charge; 
        $data->donation_chargep=$request->donation_chargep; 
        $data->single_charge=$request->single_charge; 
        $data->single_chargep=$request->single_chargep; 
        $data->min_transfer=$request->min_transfer; 
        $data->bill_charge=$request->bill_charge;
        $data->bill_chargep=$request->bill_chargep;
        $data->virtual_createcharge=$request->virtual_createcharge;
        $data->virtual_createchargep=$request->virtual_createchargep;
        $data->virtual_charge=$request->virtual_charge;
        $data->virtual_chargep=$request->virtual_chargep;
        $data->vc_min=$request->vc_min;
        $data->vc_max=$request->vc_max;
        $data->debit_currency=$request->debit_currency;
        $data->days_creditcard_liquidation=$request->days_creditcard_liquidation;
        
        if (!is_numeric($data->days_creditcard_liquidation) || !($data->days_creditcard_liquidation > 0)) {
            $data->days_creditcard_liquidation = 0;
        }
        
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    
    
    public function crypto(Request $request) {
        $data = Settings::findOrFail(1);
        $data->btc_sell=$request->btc_sell;
        $data->btc_buy=$request->btc_buy;        
        $data->eth_sell=$request->eth_sell;
        $data->eth_buy=$request->eth_buy;
        $data->min_btcbuy=$request->min_btcbuy;
        $data->min_btcsell=$request->min_btcsell;        
        $data->min_ethbuy=$request->min_ethbuy;
        $data->min_ethsell=$request->min_sell;
        $data->btc_wallet=$request->btc_wallet;
        $data->eth_wallet=$request->eth_wallet;
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }  

    public function listIntallmentFee() {
        try {
            $creditcardFeeTable = CreditcardFeeTable::getDefaultTable();

            $fees = CreditcardFeeInstallment::getInstallmentsByTable($creditcardFeeTable);

            ob_start();
            if (sizeof($fees) > 0) {
                foreach($fees as $fee) {
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $fee->installments ?>
                        </td>
                        <td class="text-center">
                            R$ <?php echo number_format($fee->tax, 2, ",", ".") ?>
                        </td> 
                        <td class="text-center">
                            <?php echo number_format($fee->tax_p, 2, ",", ".") ?>%
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="modalDeleteInstallment(<?php echo $fee->id ?>);">
                                Excluir
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td class="text-center" colspan="4">Nenhuma Faixa de Parcelamento Cadastrada.</td>
                </tr>
                <?php
            }
            $html = ob_get_contents();
            ob_end_clean();

            
            return response()->json(
                ['html' => $html, "success" => true], 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }

    public function saveInstallmentFee() {
        try {

            $installment = intval(request("installment"));
            $tax = grava_money(request("tax"));
            $taxp = grava_money(request("taxp"));

            $creditcardFeeTable = CreditcardFeeTable::getDefaultTable();
            
            CreditcardFeeInstallment::saveInstallment($creditcardFeeTable, $installment, $tax, $taxp);

            return response()->json(
                ['message' => 'Taxa criada com sucesso!', "success" => true], 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }


    public function deleteInstallmentFee() {
        try {
            $id = request("code");

            if (!is_numeric($id) || !($id > 0)) {
                throw new \Exception("A identificação do parcelamento deve ser informado.");
            }

            CreditcardFeeInstallment::where("id", $id)->delete();

            return response()->json(
                ['message' => 'Taxa excluída com sucesso!', "success" => true], 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }


    public function updatePaymentMethodsConfig(Request $request) {
        try {

            $settigns = Settings::first();
            $settigns->enable_account_payment = ($request->enable_account_payment > 0 ? 1 : 0);
            $settigns->enable_boleto_payment = ($request->enable_boleto_payment > 0 ? 1 : 0);        
            $settigns->enable_creditcard_payment = ($request->enable_creditcard_payment > 0 ? 1 : 0);
            $settigns->enable_pix_payment = ($request->enable_pix_payment > 0 ? 1 : 0);
            $res=$settigns->save();
        
            return response()->json(
                ['message' => 'Configurações salvas com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }  


}
