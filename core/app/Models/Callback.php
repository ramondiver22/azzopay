<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model {
    protected $table = "callbacks";
    protected $guarded = [];

    const ENTITY_TYPE = Array('INVOICE','WITHDRAW','FUND','PAYMENT_LINK','DONATION','STORE');

    public static function createCallback($entityType, $entityRef, $entityId, $body, $url) {

        $token='CALL-'.str_random(32);
        
        $data = Array(
            "reference" => $token,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
            "entity_type" => $entityType,
            "entity_ref" =>  $entityRef,
            "entity_id" => $entityId,
            "body" => json_encode($body),
            "host_url" => $url,
            "first_try" => null,
            "last_try" => null,
            "tries" => 0,
            "http_response_code" => null,
            "http_response_body" => null
        );

        self::create($data);
    }
}
