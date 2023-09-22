<?php

namespace Tests\Feature;

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class PokemonStoreTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testPokemonStore()
    {
        // 模擬要傳入的參數

        // 返回值為一個模型實例的陣列
        $mockedData = $this->createMockData();
        // 從該實例陣列取得user實例
        // $getUser = $mockedData['user'];

        // 將模型實例傳入,以便於在函式裡面取得id
        // 第二個參數為, 可以複寫屬性
        $data = $this->getMockData($mockedData, [
            'level' => 100
        ]);

        // dd($data);

        // 模擬使用者登入  並發送請求
        $response = $this->post('api/pokemons', $data)
            ->assertStatus(201);


            $this->assertDatabaseHas('pokemons', Arr::except($data, ['skills']));
            $this->assertDatabaseCount('pokemons', 1); // 该行应该存在
            $this->assertDatabaseMissing('pokemons', ['skills' => '[1,2,3,4]']); // 这行应该不存在，因为skills的格式不匹配
            

            // 定義預期返回的格式
        ;  // Assuming 201 means created

    }



    public function getMockData($mockData, $overrides = [])
    {

        // $mockData = $this->createMockData($role);

        // 接收數據  組裝
        $data = [
            "name" => "god",
            "race_id" => $mockData['race']->id,
            "skills" => $mockData['skills']->pluck('id')->toArray(),
            "ability_id" => $mockData['ability']->id,
            "nature_id" => $mockData['nature']->id,
            "level" => $mockData['race']->evolution_level - 1,
            // "user_id" => $mockData['user']->id
        ];

        // 用這樣的方法可以彈性的讓使用者輸入參數去修改陣列裡的數值
        return array_merge($data, $overrides);
    }



    private function createMockData($evolution_level = null)
    {
        // 創造數據返回id
        $race = Race::factory()->create(['evolution_level' => $evolution_level]);
        // $user = User::factory()->create(['role' => $role]);
        $nature = Nature::factory()->create();
        $ability = Ability::factory()->create();
        $skills = Skill::factory(4)->create();

        $race->skills()->attach($skills->pluck('id'));

        return compact('race', 'nature', 'ability', 'skills');
    }
}
