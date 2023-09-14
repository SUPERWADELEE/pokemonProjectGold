<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPokemonRequest;
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
        $pokemons = Pokemon::with(['race', 'ability', 'nature'])->get();
        return PokemonResource::collection($pokemons);
    }

    // 寶可夢新增
    public function store(StorePokemonRequest $request)
    {
        // 用validated()方法拿到已驗證過後的數據
        $validatedData = $request->validated();

        // 用輔助函數驗證此技能是否為寶可夢可以學
        Pokemon::create($validatedData);

        return response(['message' => 'Pokemon saved successfully'], 201);
    }



    // 寶可夢資料修改
    public function update(UpdatePokemonRequest $request, $id)
    {
        $pokemon = Pokemon::find($id);
        $pokemonValue = ['name', 'race_id', 'level', 'ability_id', 'nature_id', 'skills'];
        // 使用此方法更新只有實際有輸入數據的欄位才會做更新
        $pokemon->update($request->only($pokemonValue));

        return response(['message' => 'pokemon updated successfully'], 200);
    }


    public function show($id)
    {
        $pokemon = Pokemon::with(['race', 'ability', 'nature'])->find($id);
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

        try {
            if (!$evolutionLevel) {
                throw new Exception("寶可夢已是最終形態");
            }

            if ($pokemon->level > $evolutionLevel) {
                $pokemon->update(['race_id' => $pokemon->race_id + 1]);
                return response(['message' => "This Pokemon evolves."], 200);
            }

            throw new Exception("寶可夢未達進化條件");
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function search(SearchPokemonRequest $request)
    {
        // dd('fuck');
        $query = Pokemon::query();

        // // 加載關聯
        // $query->with(['race', 'ability', 'nature']);

        // 如果有提供名稱，則增加名稱的搜尋條件
        if ($name = $request->input('name')) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        // 如果有提供性格 ID，則增加性格的搜尋條件
        if ($natureId = $request->input('nature_id')) {
            $query->where('nature_id', $natureId);
        }

        if ($abilityId = $request->input('ability_id')) {
            $query->where('ability_id', $abilityId);
        }

        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        if ($race_id = $request->input('race_id')) {
            $query->where('race_id', $race_id);
        }



        // $name = $request->input('name');
        // $natureId = $request->input('nature_id');

        // $pokemons =  $query->with(['race', 'ability', 'nature'])
        //     ->orWhere('name', 'LIKE', '%' . $name . '%')
        //     ->orWhere('nature_id', $natureId)
        //     ->get();


        // 執行查詢並獲得結果
        $pokemons = $query->get();
        // dd($pokemons);

        // 使用 PokemonResource 格式化並回傳結果
        return PokemonResource::collection($pokemons);
    }



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
