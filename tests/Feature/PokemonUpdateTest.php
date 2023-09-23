<?php

namespace Tests\Feature;

use Illuminate\Support\Str;

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;


class PokemonUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */


    public function testUpdateReturnsCorrectResponse()
    {
        // 使用 Pokemon 工廠創建 1 個寶可夢
        $pokemon = Pokemon::factory()->create();

        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 保存你隨機生成的技能ID，以便於後面驗證
        $expectedSkills = $mockedData['skills']->pluck('id')->toArray();

        $data = getMockData($mockedData, ['level' => 100]);

        $response = $this->patch("api/pokemons/{$pokemon->id}", $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('pokemons', Arr::except($data, ['skills']));
        // $this->assertDatabaseCount('pokemons', 1);

        // TODO這裡的邏輯要再確認是能辨識skill有存入資料庫
        // 獲取剛剛創建的 Pokemon
        $pokemonStored = \App\Models\Pokemon::where('name', $data['name'])->firstOrFail();

        // 檢查存儲的技能是否與預期的技能相匹配

        $this->assertEqualsCanonicalizing($expectedSkills, $pokemonStored->skills);
    }


    public function testUpdateReturnsNotFoundForInvalidId()
    {
        // 選擇一個不太可能存在的ID，例如99999
        $this->patch("api/pokemons/99999")
            ->assertStatus(404)
            ->assertJson([
                "message" => "The pokemons data not found"
            ]);  // 預期獲得404 NotFound響應
    }



    // public function getMockData($mockData, $overrides = [])
    // {

    //     // $mockData = $this->createMockData($role);

    //     // 接收數據  組裝
    //     $data = [
    //         "name" => Str::random(10),
    //         "race_id" => $mockData['race']->id,
    //         "skills" => $mockData['skills']->pluck('id')->toArray(),
    //         "ability_id" => $mockData['ability']->id,
    //         "nature_id" => $mockData['nature']->id,
    //         "level" => $mockData['race']->evolution_level - 1,
    //         // "user_id" => $mockData['user']->id
    //     ];

    //     // 用這樣的方法可以彈性的讓使用者輸入參數去修改陣列裡的數值
    //     return array_merge($data, $overrides);
    // }



    // private function createMockData($evolution_level = null)
    // {
    //     // 創造數據返回id
    //     $race = Race::factory()->create(['evolution_level' => $evolution_level]);
    //     // $user = User::factory()->create(['role' => $role]);
    //     $nature = Nature::factory()->create();
    //     $ability = Ability::factory()->create();
    //     $skills = Skill::factory(4)->create();

    //     $race->skills()->attach($skills->pluck('id'));

    //     return compact('race', 'nature', 'ability', 'skills');
    // }
}
