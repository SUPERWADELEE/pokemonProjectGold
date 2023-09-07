<?php

namespace Database\Factories;

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pokemon>
 */
class PokemonFactory extends Factory
{
    protected $model = Pokemon::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'race_id' => Race::factory(),
            'level' => $this->faker->numberBetween(1, 100),
            'ability_id' => Ability::factory(),
            'nature_id' => Nature::factory(),
            'skills' => [
                Skill::factory()->create()->id,
                Skill::factory()->create()->id,
                Skill::factory()->create()->id
            ],
            
            // timestamps and softDeletes 会自动处理
        ];
    }
}
