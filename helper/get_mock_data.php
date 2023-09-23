<?php

use App\Models\Pokemon;

function getMockData($mockData, $overrides = [])
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