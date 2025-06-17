<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public $successStatus = 200;
    
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        try {
            $authorization = $request->header("Authorization");
            if (empty($authorization)) {
                throw new \Exception('Authorization header not sent', 400);
            }
            $keys = explode(":", base64_decode(str_replace("Basic ", "", $authorization)));

            if (sizeof($keys) != 2) {
                throw new \Exception('Authorization header invalid', 400);
            }

            if (empty($keys[0])) {
                throw new \Exception('Invalid public key', 400);
            }

            if (empty($keys[1])) {
                throw new \Exception('Invalid private key', 400);
            }

            $user = User::where('public_key', $keys[0])->where('secret_key', $keys[1])->orderBy('id', 'DESC')->first();
            if($user != null){
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                return response()->json(['success' => $success], $this-> successStatus);
            }
            else{
                throw new \Exception('Unauthorized', 401);
            }
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], ($ex->getCode() > 0 ? $ex->getCode() : 500));
        }
    }
    
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }
}
