<?php

namespace Tests\Feature;
use App\Models\Pokemon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\TestCase as TestingTestCase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class PokemonsIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

// 測試期望:
// 此為ＡＰＩ功能為, 取得pokemon table的所有寶可夢資料：
// 1.取得寶可夢資料
// 2.使用resource將id的部分都去關聯數據庫取出名稱並返回

    public function testIndexReturnsCorrectResponse()
    {
        // 產生使用者資訊
        // $user = User::factory()->create();
        // 產生寶可夢資訊
        $pokemons = Pokemon::factory()->count(3)->create();
        // dd($pokemons);

        // 模擬使用者登入  並發送請求
        // 定義預期返回的格式
        // $this->actingAs($user,'api')->get(route('pokemons.index'))
        $this->get('api/pokemons')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'race', 'ability', 'level', 'nature', 'photo', 'skills' ] 
                ]
                ]);
            
}
}