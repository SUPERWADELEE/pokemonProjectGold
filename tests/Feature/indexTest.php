<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\TestCase as TestingTestCase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class indexTest extends TestCase
{
    /**
     * A basic feature test example.
     */


    public function testIndexReturnsCorrectResponse()
    {
        $user = User::factory()->create();
        Pokemon::factory()->count(3)->create(['user_id' => $user->id]);

        
        $this->actingAs($user,'api')->get(route('pokemons.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'race', 'ability','level','nature','photo','skills'=>[],'host' ] // 你期望的JSON結構
            ]);
    }
}
