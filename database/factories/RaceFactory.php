<?php

namespace Database\Factories;

use App\Models\Race;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Race>
 */
class RaceFactory extends Factory
{
    protected $model = Race::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'photo' => $this->faker->imageUrl(640, 480, 'animals'),  // 示例图片URL生成
            'evolution_level' => $this->faker->numberBetween(2, 100),
            // timestamps 会自动处理
        ];
        
    }
}
