<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    
    private $successStatus  = 200;
    
    
    
    public function all() {
        try {
            
            $countries = \App\Models\Country::all();
            
            $success = Array();
            foreach ($countries as $country) {
                $success[] = $this->countryReturn($country);
            }
            return response()->json(['countries' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    public function getCountry($countryId) {
        try {
            
            if (!($countryId > 0)) {
                throw new Exception("Country Id must be greather then zero.", 400);
            }
            $country = \App\Models\Country::where("id", $countryId)->first();
            
            if (!$country) {
                throw new \Exception("Not found.", 404);
            }
            
            return response()->json(['country' => $this->countryReturn($country)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
   public function iso($iso) {
        try {
            
            if (empty($iso)) {
                throw new Exception("You must type an ISO code.", 400);
            }
            $country = \App\Models\Country::where("iso", $iso)->first();
            
            if (!$country) {
                throw new \Exception("Not found.", 404);
            }
            
            return response()->json(['country' => $this->countryReturn($country)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
   public function iso3($iso3) {
        try {
            
            if (empty($iso3)) {
                throw new Exception("You must type an ISO3 code.", 400);
            }
            $country = \App\Models\Country::where("iso3", $iso3)->first();
            
            if (!$country) {
                throw new \Exception("Not found.", 404);
            }
            
            return response()->json(['country' => $this->countryReturn($country)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    
    
    private function countryReturn($country) {
         
        return Array(
            "id" => $country->id,
            "iso" => $country->iso,
            "name" => $country->name,
            "nicename" => $country->nicename,
            "iso3" => $country->iso3,
            "numcode" => $country->numcode,
            "phonecode" => $country->phonecode
        );
        
    }
    
    
    
}
