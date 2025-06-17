<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {
    protected $table = "audit_logs";
    protected $guarded = [];


    public static function registerLog($userId, $trx, $description) {
        
        $data = Array(
            "user_id" => $userId,
            "trx" => $trx,
            "log" => $description
        );

        self::create($data);
    }
}
