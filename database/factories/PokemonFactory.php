<?php

namespace Database\Factories;

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Stringable;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pokemon>
 */

class PokemonFactory extends Factory
{
    protected $model = Pokemon::class;

    public function definition(): array
    {
        // 使用 Str::words 方法以确保字符串长度不会太长
        return [
            'name' => Str::limit(Str::words($this->faker->name(), 2, ''), 10),
            'race_id' => Race::factory(),
            'level' => $this->faker->numberBetween(1, 100),
            'ability_id' => Ability::factory(),
            'nature_id' => Nature::factory(),
            'skills' => [
                Skill::factory()->create()->id,
                Skill::factory()->create()->id,
                Skill::factory()->create()->id
            ],
        ];
    }
}

    

