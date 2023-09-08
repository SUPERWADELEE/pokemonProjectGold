<?php

namespace App\Http\Controllers;

use App\Http\Resources\RaceResource;
use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index()
    {
        // 選擇所有寶可夢的名稱和照片
        $pokemons = Race::select('id','name', 'photo')->get();
        return $pokemons;
    }

    // 返回這隻寶可夢的進化等級
    public function evolutionLevel(Race $race){
        return response(['evolution_level' => $race->evolution_level]);

    }
    

    public function skills(Race $race){
        return new RaceResource($race);

    }

}
