<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


namespace App\Lib;

class Idiom {
    
    
    public static function getLanguageVars($file) {
        
        app()->setLocale("br");
        $_lang = trans($file);
        return $_lang;
        
    }
    
}