<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CustomerController extends Controller
{
    
    private $successStatus  = 200;
    
    public function create(Request $request) {
        
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'document' => 'required|string',
                'document_type' => 'required|string',
                'zipcode' => 'required|string',
                'country_id' => 'required|integer',
                'state' => 'required|size:2|string',
                'city' => 'required|string',
                'district' => 'required|string',
                'street' => 'required|string',
                'number' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                'mobilephone' => 'required|string'
            ]);
            
            
            $document = str_replace(Array(".", "-", "/"), "", request("document"));
            $document_type = request("document_type");
            $zipcode = str_replace("-", "", request("zipcode"));
            $country_id = request("country_id");
            $state = request("state");
            $city = request("city");
            $district = request("district");
            $street = request("street");
            $number = request("number");
            
            $name = request("name");
            $email = request("email");
            $phone = request("phone");
            $mobilephone = request("mobilephone");
            
            
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
        
            $st = \App\Models\State::where("uf", strtoupper($state))->first();
         
            if (!$st) {
                throw new \Exception("Invalid state.");
            }
            
            $country = \App\Models\Country::where("id", $country_id)->first();
            
            if (!$country) {
                throw new \Exception("Invalid country.");
            }
            
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if($user->email==request("email")){
                throw new \Exception("Invalid recipient", 400);
            }
            
            $c = \App\Models\Customer::where("document", $document)->where("user_id", $user->id)->first();
            if ($c) {
                throw new \Exception("Document already registered.");
            }
            
            $c = \App\Models\Customer::where("email", $email)->where("user_id", $user->id)->first();
            if ($c) {
                throw new \Exception("E-mail already registered.");
            }
            
            if (!in_array($document_type, Array("CPF", "CNPJ"))) {
                
            }
            
            $data = Array(
                "name" => $name,
                "document" => $document,
                "document_type" => $document_type,
                "zipcode" => $zipcode,
                "country_id" => $country_id,
                "state" => strtoupper($state),
                "city" => $city,
                "district" => $district,
                "street" => $street,
                "number" => $number,
                "user_id" => $user->id,
                "email" => $email,
                "phone" => $phone,
                "mobilephone" => $mobilephone,
                "created_at" => date("Y-m-d H:i:s"),
            );
            
            $customer = \App\Models\Customer::create($data);
            
            $success = $this->userReturn($customer);
            
            return response()->json(['customer' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    public function getCustomer($customerId) {
        try {
            
            if (!is_numeric($customerId) || !$customerId > 0) {
                throw new Exception("Invalid customer id.", 400);
            }
            
            $user = \Illuminate\Support\Facades\Auth::user();
            $customer = \App\Models\Customer::where("id", $customerId)->where("user_id", $user->id)->first();
            
            if (!$customer) {
                throw new Exception("Invalid customer.", 400);
            }
            
            $success = $this->userReturn($customer);
            
            return response()->json(['customer' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }

    
    
    
    public function queryCustomers(Request $request) {
        try {
        
            $page = request("page");
            $limit = request("limit");
            
            $document = request("document");
            $name = request("name");
            $email = request("email");
            $phone = request("phone");
            $mobilephone = request("mobilephone");
            
            $page = (is_numeric($page) && $page > 0 ? ($page - 1) : 0);
            $limit = (is_numeric($limit) && $limit > 0 ? ($limit < 50 ? $limit : 50) : 10);
            
            $user = \Illuminate\Support\Facades\Auth::user();
            $customers = \App\Models\Customer::where("user_id", $user->id)->where(function ($query) use ($document, $name, $email, $phone, $mobilephone) {
                if (!empty($document)) {
                    $query->orWhere("document", "LIKE", "%{$document}%");
                }
                if (!empty($name)) {
                    $query->orWhere("name", "LIKE", "%{$name}%");
                }
                if (!empty($email)) {
                    $query->orWhere("email", "LIKE", "%{$email}%");
                }
                if (!empty($phone)) {
                    $query->orWhere("phone", "LIKE", "%{$phone}%");
                }
                if (!empty($mobilephone)) {
                    $query->orWhere("mobilephone", "LIKE", "%{$mobilephone}%");
                }
            })->skip(($page * $limit))->take($limit)->get();
            
            $success = Array();
            
            foreach ($customers as $customer) {
                $success[] = $this->userReturn($customer);
            }
            
            return response()->json(['customers' => $success, "pagination" => Array("page" => ($page+1), "limit" => $limit)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
    }
    
    private function userReturn($customer) {
         $st = \App\Models\State::where("uf", strtoupper($customer->state))->first();
         $country = \App\Models\Country::where("id", $customer->country_id)->first();
        return Array(
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
    }
    
    
}
