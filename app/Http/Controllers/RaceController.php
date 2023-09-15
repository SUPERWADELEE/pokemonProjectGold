<?php

namespace App\Http\Controllers;

use App\Http\Resources\RaceResource;
use App\Http\Resources\SkillResource;
use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index()
    {
        // 選擇所有寶可夢的名稱和照片
        $pokemons = Race::select('id','name')->get();
    
        return $pokemons;
    }

    public function evolutionLevel(Race $race){
        
        // dd($pokemon);
        if (!$race) {
            return response(['message' => 'Race not found'], 404);
        }
        return response(['evolution_level' => $race->evolution_level]);

    }
    
    public function skills(Race $race){
        $skills = $race->skills()->orderBy('id')->get();
        return SkillResource::collection($skills);
    }
    
    
    

}
