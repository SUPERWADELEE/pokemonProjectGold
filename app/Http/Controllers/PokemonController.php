<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePokemonRequest;
use App\Http\Requests\UpdatePokemonRequest;
use App\Http\Resources\PokemonResource;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use Exception;
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
    public function store(StorePokemonRequest $request)
    {
        $validatedData = $request->validated();

        Pokemon::create($validatedData);

        return response(['message' => 'Pokemon saved successfully'], 201);
    }



    // 寶可夢資料修改
    public function update(UpdatePokemonRequest $request, $id)
    {
        // dd('fuck');
        // dd($request->name);
       

       
        $pokemon = Pokemon::find($id);

        // 創建基於 $pokemon 的原始數據陣列
       

        // 使用 array_merge 合併 $originalData 和 $validationData
       
        $pokemon->update($request->only([
            'name',
            'race_id',
            'level',
            'ability_id',
            'nature_id',
            'skills'
        ]));
        
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


        // try{

        // 判定進化後,更新資料,如未到達進化條件或已封頂,則不進化
        //if (!$evolutionLevel){
        //return ...
        // }


        //if($pokemon->level > $evolutionLevel < 進化條件){
        //}} ctach()
        //return 進化條件未達到

        // $pokemon->update([
        //     'race_id' => $pokemon->race_id + 1,

        // ]);




        // if(!$evolutionLevel){
        //     return response(['message' => "寶可夢已是最終形態"], 400);
        // }

        // if ($pokemon->level > $evolutionLevel) {
        //     // dd($pokemon->race_id);
        //     $pokemon->update([
        //         'race_id' => $pokemon->race_id + 1,

        //     ]);
        //     return response(['message' => "This Pokemon evolves."], 200);
        // }

        // return response(['message' => "寶可夢未達進化條件"], 400);


        





        //     if ($evolutionLevel) {
        //         if ($pokemon->level > $evolutionLevel) {
        //             // dd($pokemon->race_id);
        //             $pokemon->update([
        //                 'race_id' => $pokemon->race_id + 1,

        //             ]);
        //             return response(['message' => "This Pokemon evolves."], 200);
        //         } else {

        //             return response(['message' => "寶可夢未達進化條件"], 400);
        //         }
        //     }

        //     return response(['message' => "寶可夢已是最終形態"], 400);
    }
}
