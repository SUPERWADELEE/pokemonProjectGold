<?php

use App\Http\Controllers\PokemonController;
use App\Models\Ability;
use Database\Seeders\NatureSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

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



Route::middleware('auth:api', 'checkStatus', 'throttle:100,1' )->group(function () {

    /**
     * pokemon管理
     */
    Route::apiResource('pokemons', PokemonController::class);
    Route::get('pokemons/search', [PokemonController::class, 'search']);
    Route::put('pokemons/{pokemon}/evolution', [PokemonController::class, 'evolution']);

    /**
     * natural管理
     */
    Route::get('natures', [NatureController::class, 'index']);
    Route::post('natures', [NatureController::class, 'store']);
    Route::patch('natures/{nature}', [NatureController::class, 'update']);

    /**
     * ability管理
     */
    Route::get('abilities', [AbilityController::class, 'index']);
    Route::post('abilities', [AbilityController::class, 'store']);
    Route::patch('abilities/{ability}', [AbilityController::class, 'update']);

    /**
     * race管理
     */
    Route::get('races', [RaceController::class, 'index']);
    Route::get('races/{race}/evolutionLevel', [RaceController::class, 'evolutionLevel']);
    Route::get('races/{race}/skill', [RaceController::class, 'skills']);


    /**
     * user管理
     */

    Route::patch('users/{user}/status', [UserController::class, 'changeUserStatus']);
    Route::patch('users/{user}/changePassword', [UserController::class,'changePassword']);



    // 登出
    Route::post('/Auth/logout', [AuthController::class, 'logout']);
});

// 註冊
Route::post('/register', [RegisterController::class, 'register']);

// 登入
Route::post('/Auth/login', [AuthController::class, 'login']);
