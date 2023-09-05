<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pokemon extends Model
{
    use HasFactory;
    use SoftDeletes;

  


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
        'skills',
        'user_id'

        // ... 其他允許的屬性 ...

    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function nature()
    {
        return $this->belongsTo(Nature::class);
    }

    public function ability()
    {
        return $this->belongsTo(Ability::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'pokemon_skill');
    }
    // app/Models/Pokemon.php

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
