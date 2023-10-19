<?php

use App\Http\Controllers\PokemonController;
use App\Models\Ability;
use Database\Seeders\NatureSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\UserController;
use App\Models\ShoppingCart;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:api', 'checkStatus', 'throttle:100000,1')->group(function () {

    /**
     * pokemon管理
     * 
     */
    // pokemon列表

    Route::apiResource('pokemons', PokemonController::class);
    // Route::get('pokemons', [PokemonController::class, 'index']);
    // Route::post('pokemons', [PokemonController::class, 'store']);
    // Route::get('pokemons/search', [PokemonController::class, 'search']);
    // Route::get('pokemons/{pokemon}', [PokemonController::class, 'show']);
    // Route::patch('pokemons/{pokemon}', [PokemonController::class, 'update']);
    // Route::delete('pokemons/{pokemon}', [PokemonController::class, 'destroy']);
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
    // ability列表
    Route::get('abilities', [AbilityController::class, 'index']);
    Route::post('abilities', [AbilityController::class, 'store']);
    Route::patch('abilities/{ability}', [AbilityController::class, 'update']);


    /**
     * race管理
     */
    // natural列表
    Route::get('races', [RaceController::class, 'index']);
    Route::get('races/{race}/evolutionLevel', [RaceController::class, 'evolutionLevel']);
    Route::get('races/{race}/skill', [RaceController::class, 'skills']);


    // Route::middleware('auth:api', 'checkStatus', 'throttle:100,1' )->group(function () {

    //     /**
    //      * pokemon管理
    //      */
    //     Route::get('pokemons/search', [PokemonController::class, 'search']);


    //     Route::put('pokemons/{pokemon}/evolution', [PokemonController::class, 'evolution']);

    //     /**
    //      * natural管理
    //      */
    //     Route::get('natures', [NatureController::class, 'index']);
    //     Route::post('natures', [NatureController::class, 'store']);
    //     Route::patch('natures/{nature}', [NatureController::class, 'update']);

    //     /**
    //      * ability管理
    //      */
    //     Route::get('abilities', [AbilityController::class, 'index']);
    //     Route::post('abilities', [AbilityController::class, 'store']);
    //     Route::patch('abilities/{ability}', [AbilityController::class, 'update']);

    //     /**
    //      * race管理
    //      */
    //     Route::get('races', [RaceController::class, 'index']);
    //     Route::get('races/{race}/evolutionLevel', [RaceController::class, 'evolutionLevel']);
    //     Route::get('races/{race}/skill', [RaceController::class, 'skills']);


    /**
     * user管理
     */
        // 使用者細節
    Route::get('user', [UserController::class, 'show']);
    Route::patch('users/{user}/status', [UserController::class, 'changeUserStatus']);
    Route::patch('users/{user}/changePassword', [UserController::class, 'changePassword']);


    // 購物車詳情
    Route::get('cart_items', [CartItemController::class, 'index']);
    Route::post('cart_items', [CartItemController::class, 'store']);
    Route::get('cart_items/total_price', [CartItemController::class, 'calculateTotalPrice']);
    Route::put('cart_items/{cart_item}', [CartItemController::class, 'update']);
    Route::delete('cart_items/{cart_item}', [CartItemController::class, 'destroy']);


    // 訂單
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']);

    // 訂單詳情
    Route::get('order_details/{order_detail}', [OrderDetailController::class, 'show']);
    Route::get('orders/{order}/order_details', [OrderDetailController::class, 'index']);
    Route::post('orders_details', [OrderDetailController::class, 'store']);

    // 購買金流
    Route::post('payments', [PaymentController::class, 'checkout']);
    
    
});

// 註冊
Route::post('/register', [RegisterController::class, 'register']);

// 登入
Route::post('/Auth/login', [AuthController::class, 'login']);
// 登出
Route::post('/Auth/logout', [AuthController::class, 'logout']);


// Route::post('pokemons/add', [PokemonController::class, 'add']);
Route::post('/payResult', [PaymentsController::class, 'notifyResponse']);



// Route::get('/add', [PokemonController::class, 'add']);
// 驗證信回傳接收
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');


Route::get('/checkVerificationStatus/{email}', [AuthController::class, 'checkVerificationStatus']);
