<?php

namespace Tests\Feature\PokemonStoreTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationSkillsTest extends TestCase
{
    use RefreshDatabase;

    // 當輸入Skill不是array的時候
    public function testPokemonStoreValidationIfSkillsIsNotAnArray()
    {
        // 模擬要傳入的參數
        $mockedData = createMockData();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['skills' => 12]);

        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The skills field must be an array."
            ]);
    }

    // 輸入的array只能1-4個技能(邊界測試)
    public function testPokemonStoreValidationSkillsBoundary()
    {
        // 如果輸入超過四個技能的話
        // 模擬要傳入的參數
        $mockedData = createMockData(15, 5);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId, 'skills' => $skillsIds]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The skills field must not have more than 4 items."
            ]);


        // 如果輸入的技能沒輸入
        $mockedData = createMockData(15, 0);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The skills field is required."
            ]);

        // 如果輸入的技能沒輸入
        $mockedData = createMockData(15, 1);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(201); // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況

        // 如果輸入的技能沒輸入
        $mockedData = createMockData(15, 4);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(201); // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
    }




    // 如果在skill輸入數字以外的值
    public function testPokemonStoreValidationSkillsNotAnInt()
    {
        // 如果輸入的技能的時候輸入不是數字
        // 模擬要傳入的參數
        $mockedData = createMockData(15, 4);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId, 'skills' => ["kk"]]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The skills.0 field must be an integer."
            ]);
    }

    // 當你輸入的值不存在skill表中
    public function testPokemonStoreValidationSkillsNotInSkillTable()
    {
        // 如果輸入的技能不在技能表裡
        $mockedData = createMockData(15, 4);

        $raceId = $mockedData['race']->id;
        $skillsIds = $mockedData['skills']->pluck('id')->toArray();

        // 將模型實例傳入,以便於在函式裡面取得id
        $data = getMockData($mockedData, ['race_id' => $raceId, 'skills' => [99999]]);
        //發送請求
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/pokemons', $data)
            ->assertStatus(422) // 422 Unprocessable Entity，用於檢查請求格式正確但含有邏輯錯誤的情況
            ->assertJson([
                "message" =>  "The selected skills.0 is invalid."
            ]);
    }


}
