<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PokemonShowTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testShowReturnsCorrectResponse()
    {
        // 產生寶可夢資訊
        $pokemons = Pokemon::factory()->count(3)->create();

        // 取得其中一個寶可夢的ID
        $pokemonId = $pokemons->first()->id;

        // 模擬請求，使用寶可夢ID作為URL參數
        $this->get("api/pokemons/{$pokemonId}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'race', 'ability', 'level', 'nature', 'photo', 'skills']
            ]);
    }

    public function testShowReturnsNotFoundForInvalidId()
    {
        // 選擇一個不太可能存在的ID，例如99999
        $this->get("api/pokemons/99999")
            ->assertStatus(404)
            ->assertJson([
                "message" => "The pokemons data not found"
            ]); // 預期獲得404 NotFound響應
    }
}
