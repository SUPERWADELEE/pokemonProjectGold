<?php

namespace Tests\Feature\PokemonStoreTest;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationLevelTest extends TestCase
{
    use RefreshDatabase;
 // 等級的邊界測試
 public function testPokemonStoreValidationLevelBoundary()
 {

     $mockedData = createMockData();

     // Test lower boundary
     $data = getMockData($mockedData, ['level' => 0]);
     $this->withHeaders([
         'Accept' => 'application/json',
     ])->post('api/pokemons', $data)
         ->assertStatus(422)
         ->assertJson(['message' => 'The level field must be at least 1.']);

     $mockedData = createMockData();
     $data = getMockData($mockedData, ['level' => 1]);
     $this->withHeaders([
         'Accept' => 'application/json',
     ])->post('api/pokemons', $data)
         ->assertStatus(201); // Assuming 201 means created

     $mockedData = createMockData();
     // Test upper boundary
     $data = getMockData($mockedData, ['level' => 100]);
     $this->withHeaders([
         'Accept' => 'application/json',
     ])->post('api/pokemons', $data)
         ->assertStatus(201); // Assuming 201 means created

     $mockedData = createMockData();
     $data = getMockData($mockedData, ['level' => 101]);
     $this->withHeaders([
         'Accept' => 'application/json',
     ])->post('api/pokemons', $data)
         ->assertStatus(422)
         ->assertJson(['message' => 'The level field must not be greater than 100.']);
 }
}