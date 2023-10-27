<?php

namespace App\Http\Controllers;

use App\Http\Resources\RaceResource;
use App\Http\Resources\SkillResource;
use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Http\Request;

/**
 * @group Race
 * Operations related to races.
 */
class RaceController extends Controller
{
    /**
     * 種族列表
     */
    public function index()
    {
        // 選擇所有寶可夢的名稱和照片
        $pokemons = Race::select('id', 'name', 'photo')->paginate(10);

        return $pokemons;
    }

    /**
     * 種族進化等級
     */
    public function evolutionLevel(Race $race)
    {

        $pokemons = Race::select('id', 'name', 'photo')->get();
        return $pokemons;
    }

    /**
     * 種族技能
     */
    public function skills(Race $race)
    {
        $skills = $race->skills()->orderBy('id')->get();
        return SkillResource::collection($skills);
    }
}
