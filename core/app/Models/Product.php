<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $table = "products";
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }    
    
    public function cat() {
        return $this->belongsTo('App\Models\Productcategory','cat_id');
    } 

    public function sold($quantity) {
        if($this->quantity_status==0){
            $this->quantity = $this->quantity - $quantity;
        }
        $this->sold = $this->sold + $quantity;
        $this->save();
    }
}
