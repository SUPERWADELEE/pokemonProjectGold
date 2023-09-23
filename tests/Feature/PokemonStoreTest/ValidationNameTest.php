<?php

namespace Tests\Feature\PokemonStoreTest;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationNameTest extends TestCase
{
    use RefreshDatabase;

    // Ｎame如果是空值
    public function testPokemonStoreValidationIfNameIsNull()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['name' => '']);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field is required."
            ]);
    }


    // 名稱如果大於15個字
    public function testPokemonStoreValidationIfNameIsGreaterThan15()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['name' => 'wdefefrgthyjukil']);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field must not be greater than 15 characters."
            ]);
    }


    // 當輸入重複name的時候
    public function testPokemonStoreValidationNameIsRepeated()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData);

        $pokemonName = $data['name'];
        $pokemon = Pokemon::factory()->create(['name' => $pokemonName]);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name has already been taken."
            ]);
    }


    // 當name不是string的時候
    public function testPokemonStoreValidationIfNameIsNotAString()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['name' => 12]);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field must be a string."
            ]);
    }
}
