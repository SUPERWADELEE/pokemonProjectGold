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
 * 這裡的種族就是寶可夢的意思
 * 
 * 
 * @authenticated
 */
class RaceController extends Controller
{
    /**
     * 取得寶可夢種族的列表。
     *
     * 此方法返回一個包含所有寶可夢種族的列表，
     * 每個種族包括其ID、名稱和照片。列表會分頁，每頁包含10個項目。
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "皮卡丘",
     *       "photo": "url_to_photo"
     *     },
     *     // 其他種族...
     *   ]
     * }
     */
    public function index()
    {
        // 選擇所有寶可夢的名稱和照片
        $pokemons = Race::select('id', 'name', 'photo')->paginate(10);

        return $pokemons;
    }

    /**
     * 取得指定種族的進化等級。
     *
     * 此方法返回指定種族的進化等級。進化等級是一個整數值，表示寶可夢在達到這個等級時可以進化。
     *
     * @urlParam race integer required 寶可夢種族的ID。示例：1
     *
     * @response {
     *   "id": 1,
     *   "evolution_level": 20
     * }
     */
    public function evolutionLevel(Race $race)
    {

        $evolutionLevel = $race->evolution_level;
        return response(['id' => $race->id, 'evolution_level' => $evolutionLevel]);
    }

    /**
     * 取得指定種族能夠學的技能。
     *
     * 此方法返回指定種族能夠學習的所有技能。技能按照它們的ID排序。
     *
     * @urlParam race integer required 寶可夢種族的ID。示例：1
     *
     * @response [
     *   {
     *     "id": 1,
     *     "name": "電擊"
     *   },
     *   // 其他技能...
     * ]
     */
    public function skills(Race $race)
    {
        $skills = $race->skills()->orderBy('id')->get();
        return SkillResource::collection($skills);
    }
}
