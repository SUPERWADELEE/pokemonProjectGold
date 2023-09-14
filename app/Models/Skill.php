<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    public function Races()
    {
        return $this->belongsToMany(Race::class);
    }

    // public function pokemons()
    // {
    //     return $this->belongsToMany(Pokemon::class, 'pokemon_skill');
    // }
}
