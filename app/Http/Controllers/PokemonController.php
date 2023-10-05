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
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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


    public function index()
    {
        // 透過JWT取得當前登入的用戶
        $user = auth()->user();

        $pokemons = $user->pokemons()->with(['user', 'ability', 'nature', 'race'])->get();
        return PokemonResource::collection($pokemons);
        // return PokemonResource::collection($pokemons);

        // $pokemons = Pokemon::with(['race', 'user', 'ability', 'nature'])
        //     ->where('user_id', $user->id)->get();

        //user->post->name// weher 一筆一筆  user::with(post)->name// whereIn


        // $array = ['wawde','hello'];
        // return PokemonResource::collection($pokemons)->additional(['ooo'=>$array]);

        // return $this->addSkill($pokemon);
        // [招式名稱陣列]




        // PokemonResource::collection($pokemons)


        // 可能可以用user角度去對關聯拿資料

        // 只獲取當前登入用戶的寶可夢
        // $pokemons = Pokemon::with(['race', 'ability', 'nature', 'user','skills'])
        //     ->where('user_id', $user->id)
        //     ->get();

        //找出user底下有什麼寶可夢
        // $pokemons = Pokemon::with(['race', 'ability', 'nature', 'user'])
        //     ->where('user_id', $user->id);
        // $pokemonSkill = $pokemons->pluck('skills');
        // // implode(',',$array);
        // dd($pokemonSkill);
        // $arr =[];
        // foreach($pokemonSkill as $index => $skill){
        //     //    $x = Skill::whereIn('id', $skill)->get()->toArray();
        //     $x = Skill::whereIn('id', $skill)->get();
        // //    dd($x->name);
        //     $arr = $x[$index];
        //     dd($arr);

        //     // dd($x[1]['name']);
        //     // $arr[] =$x;
        //     // $x[1]['name'];
        // }



        // $pokemons = $user->pokemons()->with(['race', 'ability', 'nature', 'skills'])->get();


        // dd($pokemons);
        // $pokemonSkill = $pokemons->pluck('skills');
        //         // dd($pokemonSkill);
        // dd($pokemonSkill);
        //         $arr = [];
        // foreach ($pokemonSkill as $index => $skill) {
        //     // $skillsNames = Skill::whereIn('id', $skill)->pluck('name')->toArray();
        //     $skillsNames = Skill::whereIn('id', $skill)->pluck('name');
        //     // $skillsNames = Skill::whereIn('id', $skill)->get('name');

        //     // return PokemonResource::collection($skillsNames);

        //     $arr[] = $skillsNames;
        // }

        // $combined = array_merge(["skills" => $arr], $pokemons);

        // return PokemonResource::collection($combined->get());//->additional(['skills' =>$arr]);

    }


    // 寶可夢新增
    public function store(StorePokemonRequest $request)
    {

        // dd('fuck');
        // 確認目前登入者操作權限
        // authorize 為底層有去引用Illuminate\Foundation\Auth\Access\AuthorizesRequests trait
        // 此方法通常會搭配policy用,後面參數傳入以註冊之model,然後就可以對應到該model設置的判斷權限方法
        $this->authorize('create', Pokemon::class); // "App\Models\Pokemon"  //App/policy/Pokemon

        
        // 用validated()方法只返回在 Form Request 中定義的驗證規則對應的數據

        // TODO$validatedData = $request->toArray;

        
        $validatedData = $request->validated();
        $userId = Auth::user()->id;
        $validatedData['user_id'] = $userId;
        Cache::put("payment_data_", $validatedData, now()->addMinutes(30));


        // dd($validatedData);
        if ($validatedData) {
            $key = "elc4GSPBWy0RDGAEAp4E7onj8i0qJkLw";
            $iv = "Ch5qOsn98vjTFDBP";
            $mid = "MS150428218";
            $data1 = http_build_query(array(
                'MerchantID' => $mid,
                'RespondType' => 'JSON',
                'TimeStamp' => time(),
                'Version' => '2.0',
                'MerchantOrderNo' => "test0315001" . time(),
                'Amt' => '30',
                'ItemDesc' => 'test',
                'NotifyURL' => 'https://71a2-114-33-138-55.ngrok.io/api/payResult',
                'ReturnURL' => 'http://localhost:8000/payment',
            ));
            // echo "Data=[" . $data1 . "]<br><br>";
            $edata1 = bin2hex(openssl_encrypt(
                $data1,
                "AES-256-CBC",
                $key,
                OPENSSL_RAW_DATA,
                $iv
            ));

            // log::info('Received notification:', ['all' => $request->input('TradeInfo')]);
            $hashs = "HashKey=" . $key . "&" . $edata1 . "&HashIV=" . $iv;
            $hash = strtoupper(hash("sha256", $hashs));
            // echo "MerchantID=" . $mid . "&";
            //  藍新科技股份有限公司 32
            //  線上交易-幕前支付技術串接手冊
            // echo "Version=2.0&";
            // echo "TradeInfo=" . $edata1 . "&";
            // echo "TradeSha=" . $hash;

            // dd('shout');
            return view('test', [
                'mid' => $mid,
                'edata1' => $edata1,
                'hash' => $hash
            ]);
        }



        // dd($validatedData);
        // 要如何在該陣列加入當前使用者的id
        // 記錄現在新增的寶可夢是哪個使用者的
        
    }

    // 確認目前登入者操作權限
    // $this->authorize('create', Pokemon::class);

    // // 用validated()方法只返回在 Form Request 中定義的驗證規則對應的數據
    // $validatedData = $request->validated();

    // // 要如何在該陣列加入當前使用者的id
    // $userId = Auth::user()->id;
    // $validatedData['user_id'] = $userId;

    // $pokemon = Pokemon::create($validatedData);

    // // 如果有與技能相關的數據，保存多對多關聯
    // if ($request->has('skills')) {
    //     $pokemon->skills()->sync($request->input('skills'));
    // }

    // return new PokemonResource($pokemon);

    public function add(){       
        // dd('fuck');
        // log::info('Received notification:', $request->all());

        
        $cacheKey = "payment_data_";
        // 這只是一個示範，你應該使用正確的 key
        $validatedData = Cache::get($cacheKey);
        // $validatedData = Cache::get($cacheKey);



        if ($validatedData) {
            Pokemon::create($validatedData);
            Cache::forget($cacheKey);
        }
        // return response('你尚未付款', 403);
        // return PokemonResource::make($createdData);

    }
    




    // 寶可夢資料修改
    public function update(UpdatePokemonRequest $request, Pokemon $pokemon)
    {

        // dd($pokemon);
        $pokemon->load(['ability', 'nature', 'race']);
        // 你不能去修改別人的神奇寶貝
        $this->authorize('update', $pokemon); //path:Model/pokemon-> path:model->policy
        // dd($request);
        $pokemon->update($request->validated());
        return PokemonResource::make($pokemon);
    }


    // public function show(Pokemon $pokemon)
    // {
    //     $this->authorize('show', $pokemon);
    //     $pokemon = $pokemon->with([ 'user', 'ability', 'nature','race' ])->get();
    //     return PokemonResource::make($pokemon);
    // }
    public function show(Pokemon $pokemon)
    {
        $this->authorize('show', $pokemon);
        // $pokemon->with(['user', 'ability', 'nature', 'race'])->get();
        $pokemon->load(['user', 'ability', 'nature', 'race']);
        return PokemonResource::make($pokemon);
    }




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

    // public function search(SearchPokemonRequest $request)
    // {
    //     // dd('fuck');
    //     $query = Pokemon::query();

    //     // // 加載關聯
    //     // $query->with(['race', 'ability', 'nature']);

    //     // 如果有提供名稱，則增加名稱的搜尋條件
    //     if ($name = $request->input('name')) {
    //         $query->where('name', 'LIKE', '%' . $name . '%');
    //     }

    //     // 如果有提供性格 ID，則增加性格的搜尋條件
    //     if ($natureId = $request->input('nature_id')) {
    //         $query->where('nature_id', $natureId);
    //     }

    //     if ($abilityId = $request->input('ability_id')) {
    //         $query->where('ability_id', $abilityId);
    //     }

    //     if ($level = $request->input('level')) {
    //         $query->where('level', $level);
    //     }

    //     if ($race_id = $request->input('race_id')) {
    //         $query->where('race_id', $race_id);
    //     }



    //     // $name = $request->input('name');
    //     // $natureId = $request->input('nature_id');

    //     // $pokemons =  $query->with(['race', 'ability', 'nature'])
    //     //     ->orWhere('name', 'LIKE', '%' . $name . '%')
    //     //     ->orWhere('nature_id', $natureId)
    //     //     ->get();


    //     // 執行查詢並獲得結果
    //     $pokemons = $query->get();
    //     // dd($pokemons);

    //     // 使用 PokemonResource 格式化並回傳結果
    //     return PokemonResource::collection($pokemons);
    // }
    //         return response(['message' => $e->getMessage()], 400);
    //         // TODO回應結果
    //     }
    // }

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

    // function addSkill($data =[] , $status=201){
    //     return [
    //         'skills' => '陣列的技能',
    //         'data' => $data,
    //         'status' => $status
    //     ];
    // }
}
