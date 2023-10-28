<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPokemonRequest;
use App\Http\Requests\StorePokemonRequest;
use App\Http\Requests\UpdatePokemonRequest;
use App\Http\Resources\PokemonResource;
use App\Models\Nature;
use App\Models\Pokemon;
use App\Models\Race;
use App\Models\Skill;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mockery\Expectation;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as LaravelRequest;
use Illuminate\Support\Facades\Log;


/**
 * @group Pokemons
 * Operations related to pokemons.
 * 
 * @authenticated
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

class PokemonController extends Controller
{

    /**
     * Create a new user.
     *
     * This endpoint allows you to show all pokemons.
     *
     * @group Pokemons
     * @authenticated
     *
     * @bodyParam first_name string required The first name of the user.
     * @bodyParam last_name string required The last name of the user.
     * @bodyParam email string required The email address of the user.
     */



     /**
     * 使用者寶可夢的列表
     */
    public function index()
    {
        // 透過JWT取得當前登入的用戶
        $user = auth()->user();

        $pokemons = $user->pokemons()->with(['user', 'ability', 'nature', 'race'])->get();
        return PokemonResource::collection($pokemons);
    }


    /**
     * 使用者寶可夢的新增
     */
    // 寶可夢新增
    public function store(StorePokemonRequest $request)
    {
        $validatedData = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $validatedData['user_id'] = $userId;
        Pokemon::create($validatedData);

    }

    /**
     * 使用者寶可夢的修改
     */
    // 寶可夢資料修改
    public function update(UpdatePokemonRequest $request, Pokemon $pokemon)
    {
        $pokemon->load(['ability', 'nature', 'race']);
        // 你不能去修改別人的神奇寶貝
        $this->authorize('update', $pokemon); //path:Model/pokemon-> path:model->policy
        $pokemon->update($request->validated());
        return PokemonResource::make($pokemon);
    }

    /**
     * 使用者寶可夢的詳情
     */
    public function show(Pokemon $pokemon)
    {
        $this->authorize('show', $pokemon);
        $pokemon->load(['user', 'ability', 'nature', 'race']);
        return PokemonResource::make($pokemon);
    }

    /**
     * 使用者寶可夢的刪除
     */
    public function destroy(Pokemon $pokemon)
    {
        $this->authorize('delete', $pokemon);
        // 刪除該寶可夢
        $pokemon->delete();
        // 返回成功響應
        return response(['message' => 'pokemon deleted successfully'], 200);
        return response()->noContent();
    }



    // TODO寶可夢進化等級可以用一個evolution_id 儲存
    /**
     * 使用者寶可夢的進化判斷
     */
    public function evolution(Pokemon $pokemon)
    {
        $this->authorize('evolution', $pokemon);
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
                return response(200);
            }

            throw new Exception("寶可夢未達進化條件");
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 200);
        }
    }

    public function search(SearchPokemonRequest $request)
    {

        //    TODO命名規則要注意  不要用＿
        $query = Pokemon::query();
        $name = $request->input('name');
        $nature_id = $request->input('nature_id');
        $ability_id = $request->input('ability_id');
        $level = $request->input('level');
        $race_id = $request->input('race_id');

        // TODO$query->when($request->input('name'), function($pokemons, $name){
        //         $pokemons->where('name', 'LIKE', "%$name%");
        // });
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
        //     ->Where('name', 'LIKE', '%' . $name . '%')
        //     ->Where('nature_id', $nature_id)
        //     ->get();
        $pokemons = $query->get();
        return PokemonResource::collection($pokemons);
    }

}
