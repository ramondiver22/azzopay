<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayCall extends Model {
    protected $table = "gateways_calls";
    protected $guarded = [];


    public static function createLog($userId, $host, $requestBody, $method, $response, $httpCodeResponse) {

        $data = Array(
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
            "host" => $host,
            "request_body" => $requestBody,
            "method" => $method,
            "response" => $response,
            "http_code_response" => $httpCodeResponse,
            "user_id" => $userId
        );

        self::create($data);

    }


}