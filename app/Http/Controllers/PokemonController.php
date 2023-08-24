<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        // dd('fuck');
        $allPokemons = Pokemon::find(1);
        // dd($allPokemons); 
        return response()->json($allPokemons);
        
    }
}
