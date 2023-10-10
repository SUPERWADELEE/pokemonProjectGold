<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;
    protected $table = 'races';
    protected $fillable = [
        'name',
        'photo',
        // ... 其他允許的屬性 ...

    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
