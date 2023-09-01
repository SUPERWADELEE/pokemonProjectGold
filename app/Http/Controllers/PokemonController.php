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
        // 用validated()方法只返回在 Form Request 中定義的驗證規則對應的數據
        $validatedData = $request->toArray();
        // dd($validatedData);
        

        return PokemonResource::make(Pokemon::create($validatedData));
        // return Pokemon::create($validatedData)->load(['race', 'ability', 'nature']);
        // whenload用法
        // return response(['message' => 'Pokemon saved successfully'], 201);
        #可以回資料（mia
    }



    // 寶可夢資料修改
    public function update(UpdatePokemonRequest $request, Pokemon $pokemon)
    {
        // 使用此方法更新只有實際有輸入數據的欄位才會做更新
        // $inputValue = $request->only($pokemonValue);
        $pokemon->update($request->toArray());
        // return PokemonResource::make($pokemon->load(['race', 'ability', 'nature']));
        return PokemonResource::make($pokemon);
        // return response(['message' => 'pokemon updated successfully'], 200);
    }


    public function show(Pokemon $pokemon)
    {
        
        // $pokemon = Pokemon::with(['race', 'ability', 'nature'])->find($id);
        return PokemonResource::make($pokemon);
        // return $pokemon;
        // 如何解決modelbiding錯誤問題
    }



    public function destroy(Pokemon $pokemon)
    {
        // 如果找不到寶可夢，返回一個錯誤響應
        // if (!$pokemon) {
        //     return response()->json(['message' => 'Pokemon not found'], 404);
        // }

        // 刪除該寶可夢
        $pokemon->delete();

        // 返回成功響應
        return response()->json(['message' => 'Pokemon deleted successfully'], 200);
    }



    public function evolution(Pokemon $pokemon)
    {
        // dd($pokemon);
        // 拿到寶可夢進化等級
        $pokemon->load('race');
        // $pokemon = Pokemon::with('race')->find($id);
        // 取得這隻寶可夢的進化等級
        $evolutionLevel = $pokemon->race->evolution_level;

        try {
            if (!$evolutionLevel) {
                throw new Exception("寶可夢已是最終形態");
            }

            // 因為id有照順序排所以通常id+1就會是他進化的種族的id
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
        $query = Pokemon::query();

        $name = $request->input('name');
        $nature_id = $request->input('nature_id');
        $ability_id = $request->input('ability_id');
        $level = $request->input('level');
        $race_id = $request->input('race_id');

        // 如果有提供名稱，則增加名稱的搜尋條件
        if ($name) {
            $query->where('name', 'LIKE', "%$name%");
        }

        // 如果有提供性格 ID，則增加性格的搜尋條件
        if ($nature_id) {
            $query->where('nature_id', $nature_id);
        }

        if ($ability_id) {
            $query->where('ability_id', $ability_id);
        }

        if ($level) {
            $query->where('level', $level);
        }

        if ($race_id) {
            $query->where('race_id', $race_id);
        }

        // $pokemons =  $query->with(['race', 'ability', 'nature'])
        //     ->orWhere('name', 'LIKE', '%' . $name . '%')
        //     ->orWhere('nature_id', $natureId)
        //     ->get();
        $pokemons = $query->get();
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
