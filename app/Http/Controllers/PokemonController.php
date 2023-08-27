<?php

namespace App\Http\Controllers;

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
        $allPokemons = Pokemon::all();
        return $allPokemons;
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


    // 性格修改
    public function update(Request $request, $id)
    {
        $validationData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $nature = Nature::find($id);

        if (!$nature) {
            return response(['message' => 'Nature not found'], 404);
        }

        $nature->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Nature updated successfully'], 200);
    }
}
