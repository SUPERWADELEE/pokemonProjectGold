<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PokemonDeleteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    // 寶可夢刪除之後狀態碼是否正確
    // TODO檢查資料庫是否真的刪除
    public function testDeleteReturnsCorrectResponse()
    {
        // dd('fuck');
        // dd(config('database.connections.mysql.host'));
        // 產生寶可夢資訊
        $pokemons = Pokemon::factory()->count(3)->create();

        // 取得其中一個寶可夢的ID
        $pokemonId = $pokemons->first()->id;

        // 模擬請求，使用寶可夢ID作為URL參數
        $this->delete("api/pokemons/{$pokemonId}")
            ->assertStatus(200)
            ->assertJson([
                'message' => 'pokemon deleted successfully'
            ]);
    }

    public function testDeleteReturnsNotFoundForInvalidId()
    {
        // 選擇一個不太可能存在的ID，例如99999
        $this->get("api/pokemons/99999")
            ->assertStatus(404)
            ->assertJson([
                "message" => "The pokemons data not found"
            ]);  // 預期獲得404 NotFound響應
    }
}
