<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthOriginalHub extends Model {
    protected $table = "oauth_original_hub";
    protected $guarded = [];

    public static function getCurrentValidToken() {
        $oauthOriginalHub = OauthOriginalHub::orderBy("id", "desc")->first();
        
        if ($oauthOriginalHub != null) {
            
            $today = new \DateTime(date("Y-m-d H:i:s"));
            $validity = new \DateTime(substr($oauthOriginalHub->expires_at, 0, 19));
            
            if ($today->getTimestamp() < $validity->getTimestamp()) {
                return $oauthOriginalHub;
            }
        }
        
        return null;
    }
    
}
