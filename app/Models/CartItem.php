<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    public function shoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}
