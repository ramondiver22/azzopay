<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserTax;
use App\Models\Gateway;
use App\Models\UserGateway;
use App\Models\CreditcardFeeInstallment;
use App\Models\CreditcardFeeTable;


class ClientController extends Controller {

    public function __construct() {		
        
    }

    public function taxes($id){
        $data['lang'] = parent::getLanguageVars("admin_users_page");
        $data['title']= $data['lang']["admin_users_fees_title"];
        
        $data['client']=$user=User::find($id);
        $data['tax']=UserTax::where("user_id", $user->id)->first();
        
        $data['tableActivated']= 0;

        $creditcardFeeTable = CreditcardFeeTable::getUserTable($user->id);
        if ($creditcardFeeTable->user_id > 0) {
            $data['tableActivated'] = $creditcardFeeTable->use_table;
        }
        
        $data['gatewaysIn'] = Gateway::where("status", 1)->where("sandbox", 0)->where("cashin", 1)->get();
        $data['gatewaysOut'] = Gateway::where("status", 1)->where("sandbox", 0)->where("cashout", 1)->get();
        $data['gatewaysBoleto'] = Gateway::where("status", 1)->where("sandbox", 0)->where("boleto", 1)->get();
        $data['gatewaysCreditcard'] = Gateway::where("status", 1)->where("sandbox", 0)->where("creditcard", 1)->get();

        $userGatewayCashIn = UserGateway::where("type", UserGateway::PIX)->where('user_id', $user->id)->first();
        $userGatewayCashOut = UserGateway::where("type", UserGateway::PIX_OUT)->where('user_id', $user->id)->first();
        $userGatewayBoleto = UserGateway::where("type", UserGateway::BOLETO)->where('user_id', $user->id)->first();
        $userGatewayCreditcard = UserGateway::where("type", UserGateway::CREDIT_CARD)->where('user_id', $user->id)->first();

        $data['gatewayCashIn'] = $userGatewayCashIn;
        $data['gatewayCashOut'] = $userGatewayCashOut;
        $data['gatewayBoleto'] = $userGatewayBoleto;
        $data['gatewayCreditcard'] = $userGatewayCreditcard;
        

        return view('admin.user.client-fees', $data);
    }    


    public function updateUserTaxes(Request $request) {

        try {

            $userId = $request->user_id;
            $adminId = Auth::guard('admin')->user()->id;

            UserTax::updateUserTaxes($userId, $adminId, $request);

            $success = Array("success" => true, "message" => "Taxas atualizadas com sucesso!");
            return response()->json($success, 200);
        } catch (\Exception $ex) {
            //exit($ex->getTraceAsString());
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        } 
    }

    public function comissions(Request $request) {

        try {
            $userId = $request->user_id;
            $adminId = Auth::guard('admin')->user()->id;

            UserTax::updateComissions($userId, $adminId, $request);

            return response()->json(
                ['message' => 'Comissões Atualizadas com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    } 

    

    public function listIntallmentFee() {
        try {
            $userId = request("user");

            if (!is_numeric($userId) || !($userId > 0)) {
                throw new \Exception("O id do usuário deve ser informado.");
            }

            $creditcardFeeTable = CreditcardFeeTable::getUserTable($userId);

            if ($creditcardFeeTable->user_id > 0) {
                $fees = CreditcardFeeInstallment::getInstallmentsByTable($creditcardFeeTable);
            } else {
                $fees = Array();
            }
            

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
            $userId = request("user");
            $installment = intval(request("installment"));
            $tax = grava_money(request("tax"));
            $taxp = grava_money(request("taxp"));

            $creditcardFeeTable = CreditcardFeeTable::getOrCreateUserTable($userId);
            
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

    public function enableUserCreditcardTable() {
        try {
            $userId = request("user");

            $creditcardFeeTable = CreditcardFeeTable::getOrCreateUserTable($userId);
            $creditcardFeeTable->use_table = 1;
            $creditcardFeeTable->save();

            return response()->json(
                ['message' => 'Tabela habilitada com sucesso!', "success" => true], 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }
    
    public function disableUserCreditcardTable() {
        try {
            $userId = request("user");

            $creditcardFeeTable = CreditcardFeeTable::getOrCreateUserTable($userId);
            $creditcardFeeTable->use_table = 0;
            $creditcardFeeTable->save();

            return response()->json(
                ['message' => 'Tabela desabilitada com sucesso!', "success" => true], 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }


    public function updatePaymentMethodsConfig(Request $request) {
        try {
            $userId = $request->user;
            $adminId = Auth::guard('admin')->user()->id;

            UserTax::updatePaymentMethodsConfig($userId, $adminId, $request);

            return response()->json(
                ['message' => 'Configurações salvas com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }  


    public function updatePixGateways() {
        
        try {
            $userId = request("user");
            $gateway_cashin = request("gateway_cashin");
            $gateway_cashout = request("gateway_cashout");
            $gateway_boleto = request("gateway_boleto");
            $gateway_creditcard = request("gateway_creditcard");

            if (!is_numeric($gateway_cashin)) {
                throw new \Exception("Você deve informar um gateway para cashin.");
            } else 

            if (!is_numeric($gateway_cashout)) {
                throw new \Exception("Você deve informar um gateway para cashout.");
            }

            if (!is_numeric($gateway_boleto)) {
                throw new \Exception("Você deve informar um gateway para boletos.");
            }

            if (!is_numeric($gateway_creditcard)) {
                throw new \Exception("Você deve informar um gateway para cartão de crédito.");
            }

            $gatewayCashIn = null;
            $gatewayCashOut = null;
            $gatewayCreditcard = null;
            $gatewayBoleto = null;

            if ($gateway_cashin > 0) {
                $gatewayCashIn = Gateway::where("id", $gateway_cashin)->first();
                if (!$gatewayCashIn) {
                    throw new \Exception("Gateway de cashin inválido.");
                }
            }

            if ($gateway_cashout > 0) {
                $gatewayCashOut = Gateway::where("id", $gateway_cashout)->first();
                if (!$gatewayCashOut) {
                    throw new \Exception("Gateway de cashout inválido.");
                }
            }

            if ($gateway_creditcard > 0) {
                $gatewayCreditcard = Gateway::where("id", $gateway_creditcard)->first();
                if (!$gatewayCreditcard) {
                    throw new \Exception("Gateway de de cartão de crédito inválido.");
                }
            }

            if ($gateway_boleto > 0) {
                $gatewayBoleto = Gateway::where("id", $gateway_boleto)->first();
                if (!$gatewayBoleto) {
                    throw new \Exception("Gateway de boleto inválido.");
                }
            }
            
            if ($gatewayCashIn != null) {
                $userGatewayCashIn = UserGateway::where("type", UserGateway::PIX)->where('user_id', $userId)->first();
                if ($userGatewayCashIn) {
                    $userGatewayCashIn->gateway_id = $gatewayCashIn->id;
                    $userGatewayCashIn->save();
                } else {
                    $data = Array(
                        "user_id" => $userId,
                        "gateway_id" => $gatewayCashIn->id,
                        "type" => UserGateway::PIX,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    UserGateway::create($data);
                }
            } else {
                UserGateway::where("type", UserGateway::PIX)->where('user_id', $userId)->delete();
            }

            if ($gatewayCashOut != null) {
                $userGatewayCashOut = UserGateway::where("type", UserGateway::PIX_OUT)->where('user_id', $userId)->first();
                if ($userGatewayCashOut) {
                    $userGatewayCashOut->gateway_id = $gatewayCashOut->id;
                    $userGatewayCashOut->save();
                } else {
                    $data = Array(
                        "user_id" => $userId,
                        "gateway_id" => $gatewayCashOut->id,
                        "type" => UserGateway::PIX_OUT,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    UserGateway::create($data);
                }
            } else {
                UserGateway::where("type", UserGateway::PIX_OUT)->where('user_id', $userId)->delete();
            }


            if ($gatewayBoleto != null) {
                $userGatewayBoleto = UserGateway::where("type", UserGateway::BOLETO)->where('user_id', $userId)->first();
                if ($userGatewayBoleto) {
                    $userGatewayBoleto->gateway_id = $gatewayBoleto->id;
                    $userGatewayBoleto->save();
                } else {
                    $data = Array(
                        "user_id" => $userId,
                        "gateway_id" => $gatewayBoleto->id,
                        "type" => UserGateway::BOLETO,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    UserGateway::create($data);
                }
            } else {
                UserGateway::where("type", UserGateway::BOLETO)->where('user_id', $userId)->delete();
            }


            if ($gatewayCreditcard != null) {
                $userGatewayCreditcard = UserGateway::where("type", UserGateway::CREDIT_CARD)->where('user_id', $userId)->first();
                if ($userGatewayCreditcard) {
                    $userGatewayCreditcard->gateway_id = $gatewayCreditcard->id;
                    $userGatewayCreditcard->save();
                } else {
                    $data = Array(
                        "user_id" => $userId,
                        "gateway_id" => $gatewayCreditcard->id,
                        "type" => UserGateway::CREDIT_CARD,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    UserGateway::create($data);
                }
            } else {
                UserGateway::where("type", UserGateway::CREDIT_CARD)->where('user_id', $userId)->delete();
            }
            
            return response()->json(
                ['message' => 'Gateways Atualizados com Sucesso!', "success" => true],
                 200
            );
        } catch (\Exception $ex) {
            return response()->json(['success' => false, "message" => $ex->getMessage()], 200);
        }
    }
}