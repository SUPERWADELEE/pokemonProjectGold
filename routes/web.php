<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pokemons');
});


Route::get('/pokemon/{id}', function ($id) {
    return view('pokemon', ['id' => $id]);
});



Route::get('/addRace', function () {
    return view('addRace');
});


Route::get('/addProfile', function () {
    return view('addProfile');
});


