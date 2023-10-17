<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'race_id',
        'unit_price',
        'quantity',
        'subtotal_price',
    ];



    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function race(){
        return $this->belongsTo(Race::class);
    }
}
