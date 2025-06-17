<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    function getExternalVars($file) {
        
        app()->setLocale("br");
        
        $_lang = trans($file);
        
        
        return $_lang;
        
    }
    
    function getShopVars($file) {
        app()->setLocale("br");
        
        $_lang1 = trans("shop_layout_pages");
        $_lang2 = trans($file);

        if (is_array($_lang2)) {
            $_lang = array_merge($_lang1, $_lang2);
        } 
        
        
        return $_lang;
    }

    function getLanguageVars($file) {
        
        app()->setLocale("br");
        
        $_lang1 = trans("master_layout_pages");
        $_lang2 = trans("user_layout_pages");
        $_lang3 = trans($file);
        
        if (is_array($_lang3)) {
            $_lang = array_merge($_lang1, $_lang2, $_lang3);
        } else {
            $_lang = array_merge($_lang1, $_lang2);
        }
        
        
        return $_lang;
        
    }
}
