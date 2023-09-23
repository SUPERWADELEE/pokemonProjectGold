<?php

namespace Tests\Feature\PokemonStoreTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class HappyPathTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    //  Happy Path
    // 確認傳入正確的參數後:
    //  1.是否有正確的狀態馬, 2.是否有存入資料庫

    public function testPokemonStore()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 保存你隨機生成的技能ID，以便於後面驗證
        $expectedSkills = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData);

        //發送請求
        $response = $this->post('api/pokemons', $data)
            ->assertStatus(201); // Assuming 201 means created

        // 檢查數據庫是否有相對應的數據
        // TODO這裡的邏輯要再確認是能辨識skill有存入資料庫
        $this->assertDatabaseHas('pokemons', Arr::except($data, ['skills']));
        // $this->assertDatabaseCount('pokemons', 1);

        // 獲取剛剛創建的 Pokemon
        $pokemonStored = \App\Models\Pokemon::where('name', $data['name'])->firstOrFail();

        // 檢查存儲的技能是否與預期的技能相匹配

        $this->assertEqualsCanonicalizing($expectedSkills, $pokemonStored->skills);
    }
}
