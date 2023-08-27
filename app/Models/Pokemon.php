<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;
    protected $table = 'pokemons';
    protected $casts = [
        'skills' => 'array',
    ];
    
    protected $fillable = [
        'name', 
        'race_id',
        'ability_id',
        'nature_id',
        'level',
        'skills'

        // ... 其他允許的屬性 ...

    ];
}
