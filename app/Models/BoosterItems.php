<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoosterItems extends Model
{
    protected $table = 'booster_items';

    public function boosterCart() {
        return $this->belongsTo('App\Models\BoosterCart', 'booster_cart_id', 'id');
    }
}
