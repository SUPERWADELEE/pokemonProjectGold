<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        // dd('fuck');

        $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        
        $allPokemons = $response->json();
        foreach($allPokemons['results'] as $allPokemons){
                // $names[]=$allPokemons['name'];
                // dd($allPokemons['name']);
                // dd($allPokemons['url']);
                if (isset($allPokemons['url'])) {
                    $response = Http::get($allPokemons['url']);
                    $pokemonDetail = $response->json();
                    // dd($allPokemons['name']);
                    // dd($pokemonDetail["sprites"]['front_default']);
                    Race::create([
                        'name' =>$allPokemons['name'],
                        'photo' => $pokemonDetail["sprites"]['front_default']
                    ]);
                }
            }



        // dd($allPokemons);
        // $names =[];


        #取得所有寶可夢名稱
        // $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        
        // $allPokemons = $response->json();
        // foreach($allPokemons['results'] as $allPokemons){
        //     // $names[]=$allPokemons['name'];
        //     if (isset($allPokemons['name'])) {
        //         // dd($allPokemons['name']);
        //         Race::create([
        //             'name' => $allPokemons['name']
        //         ]);
        //     }
        // }


       
        

        // foreach ($data['data']['pokemon_v2_naturename'] as $item) {
        //     if (isset($item['name'])) {
        //         Nature::create([
        //             'name' => $item['name']
        //         ]);
        //     }
        // }
        // $allPokemons = Pokemon::find(1);
        // // dd($allPokemons); 
        // return response()->json($allPokemons);
        
    }
}
