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
        $pokemons = Race::select('name', 'photo')->get();
    
        return $pokemons;
    }

    // 返回這隻寶可夢的進化等級
    public function evolutionLevel($id){
        $pokemon = Race::find($id);
        // dd($pokemon);
        if (!$pokemon) {
            return response(['error' => 'Race not found'], 404);
        }
        return response(['evolution_level' => $pokemon->evolution_level]);

    }
    

    public function skills($id){
        $skills = Race::with('skills')->find($id);
        return new RaceResource($skills);

    }

}
