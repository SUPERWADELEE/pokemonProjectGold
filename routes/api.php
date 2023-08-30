<?php

use App\Http\Controllers\PokemonController;
use App\Models\Ability;
use Database\Seeders\NatureSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\RaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * pokemon管理
 */
// pokemon列表
Route::get('pokemons', [PokemonController::class, 'index']);
Route::post('pokemons', [PokemonController::class, 'store']);
Route::get('pokemons/search', [PokemonController::class, 'search']);
Route::get('pokemons/{id}', [PokemonController::class, 'show']);
Route::patch('pokemons/{id}', [PokemonController::class, 'update']);
Route::delete('pokemons/{id}', [PokemonController::class, 'destroy']);
Route::put('pokemons/{id}/evolution', [PokemonController::class, 'evolution']);


/**
 * natural管理
 */
Route::get('natures', [NatureController::class, 'index']);
Route::post('natures', [NatureController::class, 'store']);
Route::patch('natures/{id}', [NatureController::class, 'update']);


/**
 * ability管理
 */
// ability列表
Route::get('abilities', [AbilityController::class, 'index']);
Route::post('abilities', [AbilityController::class, 'store']);
Route::patch('abilities/{id}', [AbilityController::class, 'update']);


/**
 * race管理
 */
// natural列表
Route::get('races', [RaceController::class, 'index']);
Route::get('races/{id}/evolutionLevel', [RaceController::class, 'evolutionLevel']);
Route::get('races/{id}/skill', [RaceController::class, 'skills']);

