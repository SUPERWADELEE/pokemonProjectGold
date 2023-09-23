<?php

use App\Models\Ability;
use App\Models\Nature;
use App\Models\Race;
use App\Models\Skill;

function createMockData($evolution_level = 15, $skillNumber = 4)
    {
        // 創造數據返回id
        $race = Race::factory()->create(['evolution_level' => $evolution_level]);
        // $user = User::factory()->create(['role' => $role]);
        $nature = Nature::factory()->create();
        $ability = Ability::factory()->create();
        $skills = Skill::factory($skillNumber)->create();

        $race->skills()->attach($skills->pluck('id'));

        return compact('race', 'nature', 'ability', 'skills');
    }