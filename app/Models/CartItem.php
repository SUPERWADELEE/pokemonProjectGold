<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'current_price',
        'race_id', 
    ];



    public function shoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}
