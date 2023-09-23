<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class PokemonEvolutionTest extends TestCase
{
    use RefreshDatabase;

    // 如果寶可夢可以進化, 回傳的狀態馬以及資料庫是否更新
    public function testPokemonCanEvolve()
    {
        $race = Race::factory()->create(['evolution_level' => 10]);
        // Arrange: Create a Pokemon with a level higher than its evolution level.
        $pokemon = Pokemon::factory()->create(['level' => 100, 'race_id' => $race->id]);

        // Act: Make a request to the evolution endpoint.
        $response = $this->putJson("/api/pokemons/{$pokemon->id}/evolution");

        // Assert: Check the response and the database.
        $response->assertStatus(200);
        $response->assertJson(['message' => 'This Pokemon evolves.']);
        $this->assertDatabaseHas('pokemons', [
            'id' => $pokemon->id,
            'race_id' => $pokemon->race_id + 1,
        ]);
    }

    // 如果寶可夢已在最終形態
    public function testPokemonCannotEvolveWhenAlreadyAtFinalForm()
    {
        // Arrange: Create a Pokemon which is already at its final form.
        $race = Race::factory()->create(['evolution_level' => null]);
        // Arrange: Create a Pokemon with a level higher than its evolution level.
        $pokemon = Pokemon::factory()->create(['level' => 100, 'race_id' => $race->id]);

        // Act: Make a request to the evolution endpoint.
        $response = $this->putJson("/api/pokemons/{$pokemon->id}/evolution");

        // Assert: Check the response.
        $response->assertStatus(200); // Or consider using 400 for client errors
        $response->assertJson(['message' => '寶可夢已是最終形態']);
    }

    // 如果寶可夢還不能進化
    public function testPokemonCannotEvolveWhenLevelIsNotEnough()
    {
        // Arrange: Create a Pokemon with a level lower than its evolution level.
        $pokemon = Pokemon::factory()->create(['level' => 5]);

        // Act: Make a request to the evolution endpoint.
        $response = $this->putJson("/api/pokemons/{$pokemon->id}/evolution");

        // Assert: Check the response.
        $response->assertStatus(200); // Or consider using 400 for client errors
        $response->assertJson(['message' => '寶可夢未達進化條件']);
    }



    // 如果找不到寶可夢
    public function testEvolutionReturnsNotFoundForInvalidId()
    {
        // 選擇一個不太可能存在的ID，例如99999
        $this->put("api/pokemons/99999/evolution")
            ->assertStatus(404)
            ->assertJson([
                "message" => "The pokemons data not found"
            ]); // 預期獲得404 NotFound響應
    }
}
