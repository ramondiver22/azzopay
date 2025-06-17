<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    
    private $successStatus  = 200;
    
    
    
    public function all() {
        try {
            
            $states = \App\Models\State::all();
            
            $success = Array();
            foreach ($states as $state) {
                $success[] = $this->stateReturn($state);
            }
            return response()->json(['states' => $success], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    public function getState($stateId) {
        try {
            
            if (!($stateId > 0)) {
                throw new Exception("State Id must be greather then zero.", 400);
            }
            $state = \App\Models\State::where("id", $stateId)->first();
            
            if (!$state) {
                throw new \Exception("Not found.", 404);
            }
            
            return response()->json(['state' => $this->stateReturn($state)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
   public function uf($uf) {
        try {
            
            if (empty($uf)) {
                throw new Exception("You must type an UF code.", 400);
            }
            $state = \App\Models\State::where("uf", $uf)->first();
            
            if (!$state) {
                throw new \Exception("Not found.", 404);
            }
            
            return response()->json(['state' => $this->stateReturn($state)], $this->successStatus);
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], (($ex->getCode() > 0 && is_numeric($ex->getCode())) ? $ex->getCode() : 400));
        }
        
    }
    
    
    private function stateReturn($state) {
         
        return Array(
            "id" => $state->id,
            "name" => $state->name,
            "uf" => $state->uf,
            "ibge" => $state->ibge,
            "ddd" => $state->ddd
        );
        
    }
    
    
}
