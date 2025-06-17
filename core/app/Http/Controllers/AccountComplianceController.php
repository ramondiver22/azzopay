<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;
use Illuminate\Support\Facades\Auth;
use App\Models\Compliance;
use App\Models\Countrysupported;
use App\Models\Country;
use App\Models\Settings;
use App\Models\User;
use App\Models\State;
use App\Models\Banksupported;
use App\Lib\BancoOriginal\BancoOriginalAccount;


class AccountComplianceController extends Controller {

        
    public function __construct() {		
        
    }

    public function complianceType(){
        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_module_title"];
        
        
        $compliance = Compliance::where("user_id", Auth::guard('user')->user()->id)->first();
        if (!$compliance) {
            $compliance = new Compliance();
            $compliance->user_id = Auth::guard('user')->user()->id;
            $compliance->save();
        }

        $data['compliance'] = $compliance;

        return view('user.compliance.type', $data);
    }    
    
    public function personalCompliance() {
        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_module_title"];
        
        $data['country']=Countrysupported::wherestatus(1)->get();
        $data['states']= State::orderBy("name", "asc")->get();
        $data['bcountry']=Country::where('phonecode', '!=', 0)->get();
        $data['nationality']=Country::all();
        $set=Settings::first();
        $user = User::find(Auth::guard('user')->user()->id);

        $compliance = Compliance::where("user_id", $user->id)->first();
        if (!$compliance) {
            $compliance = new Compliance;
            $compliance->user_id = $user->id;
            $compliance->save();
        }
        $data['settings'] = $set;
        $data['user'] = $user;
        $data['compliance'] = $compliance;


        
        return view('user.compliance.personal', $data);
    }


    public function personalDocuments() {

        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_personal_document_title"];
        $user = User::find(Auth::guard('user')->user()->id);
        $compliance = Compliance::where("user_id", $user->id)->first();
        $data['compliance'] = $compliance;
        return view('user.compliance.personal_document', $data);
    }
    
    
    public function businessCompliance() {
        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_module_title"];
        
        $data['country']=Countrysupported::wherestatus(1)->get();
        $data['states']= State::orderBy("name", "asc")->get();
        $data['bcountry']=Country::where('phonecode', '!=', 0)->get();
        $set=Settings::first();
        $user = User::find(Auth::guard('user')->user()->id);
        $compliance = Compliance::where("user_id", $user->id)->first();
        if (!$compliance) {
            $compliance = new Compliance;
            $compliance->user_id = $user->id;
            $compliance->save();
        }
        $data['settings'] = $set;
        $data['user'] = $user;
        $data['compliance'] = $compliance;
        return view('user.compliance.business', $data);
    }



    public function businessDocuments() {

        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_business_document_title"];
        $user = User::find(Auth::guard('user')->user()->id);
        $compliance = Compliance::where("user_id", $user->id)->first();
        $data['compliance'] = $compliance;
        return view('user.compliance.business_document', $data);
    }
    
    
    
    public function personalComplianceSave(Request $request) {
        
        try {
            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->saveCompliance($user->id, $request->all(), "pf");
            
            $json = Array(
                "success" => true,
                "message" => "Dados recebidos com sucesso!",
                "redirect" => route("user.personal-documents")
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }




    public function businessComplianceSave(Request $request) {
        
        try {
            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->saveCompliance($user->id, $request->all(), "pj");
            
            $json = Array(
                "success" => true,
                "message" => "Dados recebidos com sucesso!",
                "redirect" => route("user.business-documents")
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }


    public function personalDocumentFiles(Request $request) {
        try {

            $data = Array(
                "id_type" => request("id_type"),
                "document" => request("document"),
            );

            if ($request->hasFile('personal_id_document_front')) {
                $documentFront = $request->file('personal_id_document_front');
                $filename = 'document_front_'.time().'.'.$documentFront->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($documentFront)->save($location);
                $data["idcard"] = $filename;
            }  
            
            if ($request->hasFile('personal_id_document_back')) {
                $documentBack = $request->file('personal_id_document_back');
                $filename = 'document_back_'.time().'.'.$documentBack->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($documentBack)->save($location);
                $data["idcard_back"] = $filename;
            }  

            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->updateClientDocumentFiles($user->id, $data);
            
            $json = Array(
                "success" => true,
                "message" => "Arquivo recebido com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }

    

    public function personalProofOfResidenceFiles(Request $request) {
        try {

            $proof = "";

            if ($request->hasFile('personal_id_document_residence')) {
                $residenceProof = $request->file('personal_id_document_residence');
                $filename = 'residence_proof_'.time().'.'.$residenceProof->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($residenceProof)->save($location);
                $proof = $filename;
            }  

            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->updateClientProofFiles($user->id, $proof);
            
            $json = Array(
                "success" => true,
                "message" => "Arquivo recebido com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }



    public function personalSelfieFiles(Request $request) {
        try {

            $selfie = "";

            if ($request->hasFile('personal_id_document_selfie')) {
                $documentSelfie = $request->file('personal_id_document_selfie');
                $filename = 'selfie_'.time().'.'.$documentSelfie->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($documentSelfie)->save($location);
                $selfie = $filename;
            }  

            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->updateClientSelfieFiles($user->id, $selfie);
            
            $json = Array(
                "success" => true,
                "message" => "Arquivo recebido com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }


    public function businessProofOfResidenceFiles(Request $request) {
        try {

            $proof = "";

            if ($request->hasFile('business_id_document_residence')) {
                $documentProof = $request->file('business_id_document_residence');
                $filename = 'business_proof_address_'.time().'.'.$documentProof->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($documentProof)->save($location);
                $proof = $filename;
            }  

            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->updateBusinessProofFiles($user->id, $proof);
            
            $json = Array(
                "success" => true,
                "message" => "Arquivo recebido com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    } 
    
    public function businessRegistryFiles(Request $request) {
        try {

            $registry = "";

            if ($request->hasFile('business_id_document_institutional')) {
                $documentRegistry = $request->file('business_id_document_institutional');
                $filename = 'national_registry_'.time().'.'.$documentRegistry->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($documentRegistry)->save($location);
                $registry = $filename;
            }  

            $user = Auth::guard('user')->user();
            
            $compliance = new Compliance();
            $compliance->updateBusinessRegistryFiles($user->id, $registry);
            
            $json = Array(
                "success" => true,
                "message" => "Arquivo recebido com sucesso!"
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }


    public function originalHubCompliance() {
        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_original_hub_page_title"];
        
       
        $user = User::find(Auth::guard('user')->user()->id);
        $compliance = Compliance::where("user_id", $user->id)->first();
        $data['bnk']=Banksupported::wherecountry_id($user->country)->get();

        $data['user'] = $user;
        $data['compliance'] = $compliance;
        
        return view('user.compliance.original', $data);
    }


    
    public function originalHubComplianceSave() {
        try {

            $supportedBank = Banksupported::where("id", request("bank"))->first();
            
            if (!$supportedBank) {
                throw new Exception("Banco inválido. Por favor, selecione um banco válido.");
            }

            $bankData = Array(
                "bank" => $supportedBank->code,
                "branch" => request("branch"),
                "account" => request("account")
            );

            $user = User::find(Auth::guard('user')->user()->id);
            $compliance = Compliance::where("user_id", $user->id)->first();

            $bancoOriginalAccount = new BancoOriginalAccount();
            $result = $bancoOriginalAccount->createAccount($compliance, $bankData);

            /*
            $result = (object) Array(
                "uuid" => "da3e1fd6-8b91-479c-aac2-09e904c48b06"
            );
            */

            $user->original_hub_uuid = $result->uuid;
            $user->save();

            $json = Array(
                "success" => true,
                "message" => "Cadastro recebido com sucesso! Aguarde para ser redirecionado!",
                "redirect" => route("user.original-hub-token")
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }


    public function originalHubComplianceToken() {

        $data['lang'] = parent::getLanguageVars("user_compliance_page");
        $data['title']= $data['lang']["compliance_original_hub_page_title"];
        
       
        $user = User::find(Auth::guard('user')->user()->id);
        $compliance = Compliance::where("user_id", $user->id)->first();
        $data['bnk']=Banksupported::wherecountry_id($user->country)->get();

        $data['user'] = $user;
        $data['compliance'] = $compliance;
        
        return view('user.compliance.original_token', $data);

    }


    public function originalHubComplianceValidate() {

        try {
           
            $token = request("code");

            if (empty($token)) {
                throw new \Exception("É necessário informar o código de validação.");
            }
            $user = User::find(Auth::guard('user')->user()->id);
            $compliance = Compliance::where("user_id", $user->id)->first();

            $bancoOriginalAccount = new BancoOriginalAccount();
            $result = $bancoOriginalAccount->verify($user->original_hub_uuid, $user->original_hub_uuid, $compliance->cpf, $token);

            $json = Array(
                "success" => true,
                "message" => "Código Validado com Sucesso!",
                "redirect" => route("user.account-compliance")
            );
            
            return response()->json($json, 200);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message'=> $ex->getMessage()], 200);
        }
    }
}