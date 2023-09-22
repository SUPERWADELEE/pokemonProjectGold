<?php

namespace Tests\Feature;

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Pokemon;
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

    //  Happy Path
    // 確認傳入正確的參數後, 是否有正確的狀態馬, 是否有存入資料庫
    public function testPokemonStore()
    {
        // 模擬要傳入的參數
        $mockedData = $this->createMockData();

        // 保存你隨機生成的技能ID，以便於後面驗證
        $expectedSkills = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = $this->getMockData($mockedData);

        //發送請求
        $response = $this->post('api/pokemons', $data)
            ->assertStatus(201); // Assuming 201 means created

        // 檢查數據庫是否有相對應的數據
        $this->assertDatabaseHas('pokemons', Arr::except($data, ['skills']));
        // $this->assertDatabaseCount('pokemons', 1);

        // 獲取剛剛創建的 Pokemon
        $pokemonStored = \App\Models\Pokemon::where('name', $data['name'])->firstOrFail();

        // 檢查存儲的技能是否與預期的技能相匹配

        $this->assertEqualsCanonicalizing($expectedSkills, $pokemonStored->skills);
    }



    // validation-name
    public function testPokemonStoreValidationIfNameIsNull()
    {
        // 模擬要傳入的參數
        $mockedData = $this->createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = $this->getMockData($mockedData, ['name' => '']);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field is required."
            ]);
    }

    public function testPokemonStoreValidationIfNameIsGreaterThan15()
    {
        // 模擬要傳入的參數
        $mockedData = $this->createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = $this->getMockData($mockedData, ['name' => 'wdefefrgthyjukil']);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field must not be greater than 15 characters."
            ]);
    }

    // public function testPokemonStoreValidationNameIsRepeated()
    // {
    //     // 模擬要傳入的參數
    //     $mockedData = $this->createMockData();

    //     $pokemons = Pokemon::factory()->count(3)->create();
    //     $pokemon = $pokemons->pluck('name')->first();

    //     dd($pokemon);
    //     // 將模型實例傳入,以便於在函式裡面取得id
    //     $data = $this->getMockData($mockedData, ['name' => $pokemon]);

    //     //發送請求
    //     $response = $this->withHeaders([
    //         'Accept' => 'application/json',
    //     ])->post('api/pokemons', $data)
    //         ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
    //         ->assertJson([
    //             "message" => "The name has already been taken."
    //         ]);
    // }

    public function testPokemonStoreValidationIfNameIsNotAString()
    {
        // 模擬要傳入的參數
        $mockedData = $this->createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = $this->getMockData($mockedData, ['name' => 12]);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" => "The name field must be a string."
            ]);
    }



    // 等級的邊界測試
    public function testPokemonStoreValidationLevelBoundary()
    {

        $mockedData = $this->createMockData();

        // Test lower boundary
        $data = $this->getMockData($mockedData, ['level' => 0]);
        $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422)
            ->assertJson(['message' => 'The level field must be at least 1.']);

        $mockedData = $this->createMockData();
        $data = $this->getMockData($mockedData, ['level' => 1]);
        $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(201); // Assuming 201 means created

        $mockedData = $this->createMockData();
        // Test upper boundary
        $data = $this->getMockData($mockedData, ['level' => 100]);
        $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(201); // Assuming 201 means created

        $mockedData = $this->createMockData();
        $data = $this->getMockData($mockedData, ['level' => 101]);
        $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422)
            ->assertJson(['message' => 'The level field must not be greater than 100.']);
    }




    // public function testPokemonStoreValidationSkillsBoundary()
    // {
    //     // 模擬要傳入的參數
    //     $mockedData = $this->createMockData();

    //     // 將模型實例傳入,以便於在函式裡面取得id
    //     $data = $this->getMockData($mockedData, ['race_id' => 1,'skills' => [13,14,15,20,22]]);

    //     dd($data);
    //     //發送請求
    //     $response = $this->withHeaders([
    //         'Accept' => 'application/json',
    //     ])->post('api/pokemons', $data)
    //         ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
    //         ->assertJson([
    //             "message" =>  "The skills field must not have more than 4 items."
    //         ]);
    // }

    public function testPokemonStoreValidationIfSkillsIsNotAnArray()
    {
        // 模擬要傳入的參數
        $mockedData = $this->createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = $this->getMockData($mockedData, ['skills' => 12]);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The skills field must be an array."
            ]);
    }











    public function getMockData($mockData, $overrides = [])
    {

        // $mockData = $this->createMockData($role);

        $pokemon = Pokemon::factory()->make();
        // 接收數據  組裝
        // dd($pokemon->name);
        $data = [
            "name" => $pokemon->name,
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



    private function createMockData($evolution_level = 15)
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
