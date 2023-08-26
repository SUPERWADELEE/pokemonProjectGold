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
        $validationData = $request->validate([
            'name' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'level' => 'required|int|min:1|max:100',
            'ability' => 'required|string|max:255',
            'nature' => 'required|string|max:255',
            'skills' => 'required|array|min:1|max:4',
        ]);

        Pokemon::create([
            'name' => $validationData['name'],
            'race' => $validationData['race'],
            'level' => $validationData['level'],
            'ability' => $validationData['ability'],
            'nature' => $validationData['nature'],
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
