<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Compliance;
use App\Models\Countrysupported;
use App\Models\Country;
use App\Models\Settings;
use App\Models\User;
use App\Models\State;
use App\Models\Banksupported;
use App\Lib\BancoOriginal\BancoOriginalAccount;


class UserComplianceController extends Controller {

        
    public function __construct() {		
        
    }

    public function complianceType($userId){
        $data['lang'] = parent::getLanguageVars("admin_users_page");
        $data['title']= $data['lang']["admin_users_compliance_title"];
        
        $user = User::find($userId);
        $data['user'] = $user;

        $compliance = Compliance::where("user_id", $user->id)->first();
        if (!$compliance) {
            $compliance = new Compliance();
            $compliance->user_id = $user->id;
            $compliance->save();
        }

        $data['compliance'] = $compliance;

        return view('admin.user.compliance', $data);
    }    
    
    public function personalCompliance($userId) {

        $data['lang'] = parent::getLanguageVars("admin_users_page");
        $data['title']= $data['lang']["admin_user_compliance_module_title"];
        

        $set=Settings::first();
        $user = User::find($userId);

        $country = null;
        $addressCountry = null;
        $state = null;
        $documentType = "";

        $compliance = Compliance::where("user_id", $user->id)->first();
        if (!$compliance) {
            $compliance = new Compliance();
            $compliance->user_id = $user->id;
            $compliance->save();
        } else {
            if ($compliance->nationality > 0) {
                $country = Country::where('id', $compliance->nationality)->first();
            }
            if ($compliance->address_country_id > 0) {
                $addressCountry = Country::where('id', $compliance->address_country_id)->first();
            }
            if (!empty($compliance->address_state)) {
                $state = State::where('uf', $compliance->address_state)->first();
            }
        }
        
        switch($compliance->id_type) {
            case "National ID": $documentType = $data['lang']["admin_users_compliance_personal_document_id_card"]; break;
            case "International Passport": $documentType = $data['lang']["admin_users_compliance_personal_document_passport"]; break;
            case "Voters Card": $documentType = $data['lang']["admin_users_compliance_personal_document_voters_card"]; break;
            case "Driver License": $documentType = $data['lang']["admin_users_compliance_personal_document_drivers_license"]; break;
        }
        

        $data['settings'] = $set;
        $data['user'] = $user;
        $data['compliance'] = $compliance;
        $data['documentType'] = $documentType;
        $data['country'] = $country;
        $data['addressCountry'] = $addressCountry;
        $data['state'] = $state;

        
        return view('admin.user.personal', $data);
    }


    public function businessCompliance($userId) {
        $data['lang'] = parent::getLanguageVars("admin_users_page");
        $data['title']= $data['lang']["admin_user_compliance_module_title"];
        

        $addressCountry = null;
        $state = null;

        $set=Settings::first();
        $user = User::find($userId);
        
        $compliance = Compliance::where("user_id", $user->id)->first();

        if (!$compliance) {
            $compliance = new Compliance();
            $compliance->user_id = $user->id;
            $compliance->save();
        } else {
            if ($compliance->office_address_country_id > 0) {
                $addressCountry = Country::where('id', $compliance->office_address_country_id)->first();
            }
            if (!empty($compliance->office_address_state)) {
                $state = State::where('uf', $compliance->office_address_state)->first();
            }
        }

        $data['settings'] = $set;
        $data['user'] = $user;
        $data['compliance'] = $compliance;
        $data['addressCountry'] = $addressCountry;
        $data['state'] = $state;
        return view('admin.user.business', $data);

    }

    
    public function rejectPersonalDocument(Request $request) {
        
        try {
            $data['lang'] = parent::getLanguageVars("exceptions_page");

            $userId = request("user");
            $type = request("type");
            $message = request("message");
            
            if (!is_numeric($userId) || !($userId > 0)) {
                throw new \Exception($data['lang']['exceptions_user_not_found_or_invalid']);
            }
            
            $msg = "";
            switch($type) {
                case "d": 
                    Compliance::rejectPersonalDocument($userId, $message);
                    $msg = "Documento rejeitado";
                    break;
                case "s": 
                    Compliance::rejectPersonalSelfie($userId, $message);
                    $msg = "Selfie rejeitada";
                    break;
                case "r": 
                    Compliance::rejectPersonalProof($userId, $message);
                    $msg = "Comprovante de Residência rejeitado";
                    break;
                case "p": 
                    Compliance::rejectPersonalCompliance($userId, $message);
                    $msg = "Compliance rejeitado";
                    break;
                default:
                    throw new \Exception("Tipo de documento inválido.");
            }
            
            $json = Array(
                "success" => true,
                "message" => "{$msg} com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }


    
    public function approvePersonalDocument(Request $request) {
        
        try {
            $data['lang'] = parent::getLanguageVars("exceptions_page");

            $userId = request("user");
            $type = request("type");
            
            if (!is_numeric($userId) || !($userId > 0)) {
                throw new \Exception($data['lang']['exceptions_user_not_found_or_invalid']);
            }
            
            $msg = "";
            switch($type) {
                case "d": 
                    Compliance::approvePersonalDocument($userId);
                    $msg = "Documento aprovado";
                    break;
                case "s": 
                    Compliance::approvePersonalSelfie($userId);
                    $msg = "Selfie aprovada";
                    break;
                case "r": 
                    Compliance::approvePersonalProof($userId);
                    $msg = "Comprovante de Residência aprovado";
                    break;
                case "p": 
                    Compliance::approvePersonalCompliance($userId);
                    $msg = "Compliance aprovado";
                    break;
                default:
                    throw new \Exception("Tipo de documento inválido.");
            }

            
            $json = Array(
                "success" => true,
                "message" => "{$msg} com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }

    public function rejectBusinessDocument(Request $request) {
        
        try {
            $data['lang'] = parent::getLanguageVars("exceptions_page");

            $userId = request("user");
            $type = request("type");
            $message = request("message");
            
            if (!is_numeric($userId) || !($userId > 0)) {
                throw new \Exception($data['lang']['exceptions_user_not_found_or_invalid']);
            }

            $msg = "";
            switch($type) {
                case "c": 
                    Compliance::rejectBusinessRegistry($userId, $message);
                    $msg = "Comprovante de cadastro de CNPJ rejeitado";
                    break;
                case "r": 
                    Compliance::rejectBusinessProof($userId, $message);
                    $msg = "Comprovante de endereço rejeitado";
                    break;
                case "p": 
                    Compliance::rejectBusinessCompliance($userId, $message);
                    $msg = "Compliance rejeitado";
                    break;
                default:
                    throw new \Exception("Tipo de documento inválido.");
            }
            
            $json = Array(
                "success" => true,
                "message" => "{$msg} com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }
    
    
    public function approveBusinessDocument(Request $request) {
        
        try {
            $data['lang'] = parent::getLanguageVars("exceptions_page");

            $userId = request("user");
            $type = request("type");
            
            if (!is_numeric($userId) || !($userId > 0)) {
                throw new \Exception($data['lang']['exceptions_user_not_found_or_invalid']);
            }
            
                 
            $msg = "";
            switch($type) {
                case "c": 
                    Compliance::approveBusinessRegistry($userId);
                    $msg = "Comprovante de cadastro de CNPJ aprovado";
                    break;
                case "r": 
                    Compliance::approveBusinessProof($userId);
                    $msg = "Comprovante de Endereço aprovado";
                    break;
                case "p": 
                    Compliance::approveBusinessCompliance($userId);
                    $msg = "Compliance aprovado";
                    break;
                default:
                    throw new \Exception("Tipo de documento inválido.");
            }

            
            $json = Array(
                "success" => true,
                "message" => "{$msg} com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }
}