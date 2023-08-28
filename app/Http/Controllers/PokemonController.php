<?php

namespace App\Http\Controllers;

use App\Http\Resources\PokemonResource;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mockery\Expectation;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as LaravelRequest;

class PokemonController extends Controller
{
    public function index()
    {
        // 寶可夢詳情
        $pokemons = Pokemon::with(['race', 'ability', 'nature', 'race.skills'])->get();
        return PokemonResource::collection($pokemons);
    }

    // 寶可夢新增
    public function store(Request $request)
    {
        // dd($request->skills);
        $validationData = $request->validate([
            'name' => 'required|string|max:255',
            'race_id' => 'required|integer|exists:races,id',
            'ability_id' => 'required|integer|exists:abilities,id',
            'nature_id' => 'required|integer|exists:natures,id',
            'level' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    $race = Race::find($request->input('race_id'));
                    if ($race && $value >= $race->evolution_level) {
                        $fail("The level must be less than or equal to the race's minimum evolution level.");
                    }
                }
            ],
            'skills' => [
                'required',
                'array',
                'min:1',
                'max:4',
                function ($attribute, $value, $fail) use ($request) {
                    $race = Race::find($request->input('race_id'));
                    if (!$race) {
                        return $fail('The race_id is invalid.');
                    }
                    $allowedSkills = $race->skills->pluck('id')->toArray();
                    // dd($allowedSkills);
                    // dd($value);
                    foreach ($value as $skillId) {
                        if (!in_array($skillId, $allowedSkills)) {
                            return $fail("The skill with ID {$skillId} is not allowed for this race.");
                        }
                    }
                }
            ]
        ]);


        Pokemon::create([
            'name' => $validationData['name'],
            'race_id' => $validationData['race_id'],
            'level' => $validationData['level'],
            'ability_id' => $validationData['ability_id'],
            'nature_id' => $validationData['nature_id'],
            'skills' => $validationData['skills'],
        ]);

        return response(['message' => 'Pokemon saved successfully'], 201);
    }



    // 寶可夢資料修改
    public function update(Request $request, $id)
    {
        // dd('fuck');
        // dd($request->name);
        $validationData = $request->validate([

            'name' => 'string|max:255',
            'race_id' => 'integer|exists:races,id',
            'ability_id' => 'integer|exists:abilities,id',
            'nature_id' => 'integer|exists:natures,id',
            'level' => 'integer||min:1|max:100',
            'skills' => [
                'array',
                'min:1',
                'max:4',
                function ($attribute, $value, $fail) use ($request) {
                    $race = Race::find($request->input('race_id'));
                    if (!$race) {
                        return $fail('The race_id is invalid.');
                    }
                    $allowedSkills = $race->skills->pluck('id')->toArray();
                    // dd($allowedSkills);
                    // dd($value);
                    foreach ($value as $skillId) {
                        if (!in_array($skillId, $allowedSkills)) {
                            return $fail("The skill with ID {$skillId} is not allowed for this race.");
                        }
                    }
                }
            ]
        ]);

        $pokemon = Pokemon::find($id);
        // dd($pokemon);
        // dd( $validationData['level']);
        $pokemon->update([
            'name' => $validationData['name'] ?? $pokemon->name,
            'race_id' => $validationData['race_id'] ?? $pokemon->race_id,
            'level' => $validationData['level'] ?? $pokemon->level,
            'ability_id' => $validationData['ability_id'] ?? $pokemon->ability_id,
            'nature_id' => $validationData['nature_id'] ?? $pokemon->nature_id,
            'skills' => $validationData['skills'] ?? $pokemon->skills,
        ]);


        return response(['message' => 'pokemon updated successfully'], 200);
    }


    public function show($id)
    {
        $pokemon = Pokemon::with(['race', 'ability', 'nature', 'race.skills'])->find($id);
        return new PokemonResource($pokemon);
    }



    public function destroy($id)
    {
        // 尋找該ID的寶可夢
        $pokemon = Pokemon::find($id);

        // 如果找不到寶可夢，返回一個錯誤響應
        if (!$pokemon) {
            return response()->json(['message' => 'Pokemon not found'], 404);
        }

        // 刪除該寶可夢
        $pokemon->delete();

        // 返回成功響應
        return response()->json(['message' => 'Pokemon deleted successfully'], 200);
    }


    
    public function evolution($id)
    {
        // 拿到寶可夢進化等級
        $pokemon = Pokemon::with('race')->find($id);
        $evolutionLevel = $pokemon->race->evolution_level;


        // 判定進化後,更新資料,如未到達進化條件或已封頂,則不進化
        if ($evolutionLevel) {
            if ($pokemon->level > $evolutionLevel) {
                // dd($pokemon->race_id);
                $pokemon->update([
                    'race_id' => $pokemon->race_id + 1,

                ]);
                return response(['message' => "This Pokemon evolves."], 200);
            } else {

                return response(['message' => "寶可夢未達進化條件"], 400);
            }
        }

        return response(['message' => "寶可夢已是最終形態"], 400);
    }
}
