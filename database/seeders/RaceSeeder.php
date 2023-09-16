<?php

namespace Database\Seeders;

use App\Models\Nature;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// use Illuminate\Http\Request;

use Mockery\Expectation;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Mockery\Exception;
use Illuminate\Support\Facades\Http;



class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        // 姓名、照片、進化等級、技能一次全部拿,全部存
        // $batchSize = 1; // 可以根據需要調整
        // // 1011
        // $totalPokemons = 2; // 根據你的實際數量調整

        // for ($i = 1; $i < $totalPokemons; $i += $batchSize) {
        //     // 取得名稱、照片、招式
        //     $response1 = Http::get("https://pokeapi.co/api/v2/pokemon/$i");
        //     $pokemonDetail = $response1->json();
        //     // dd($allPokemonsArray);
        //     $name = $pokemonDetail['name'];
        //     // dd($name);
        //     $photo = $pokemonDetail["sprites"]['front_default'];
        //     $moves = $pokemonDetail['moves'];
        //     $moveNames = [];
        //     // 取出此寶可夢所能學的所有的招式名稱
        //     foreach ($moves as $moves) {
        //         $moveNames[] = $moves['move']['name'];
        //         // dd($moveNames);
        //     }

        //     // 取得進化等級,這在pokemon-species的進化鏈裡面

        //     $pokemonData = Http::get("https://pokeapi.co/api/v2/pokemon-species/$i")->json();
        //     $getEvolutionChain = $pokemonData["evolution_chain"]['url'];
        //     $evolutionChain = Http::get($getEvolutionChain)->json();
        //     $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);
        // dd($minLevel);

        // $race = Race::create([
        //     'name' => $name,
        //     'photo' => $photo,
        //     'evolution_level' => $minLevel
        // ]);

        // foreach ($moveNames as $moveName) {
        //     // 先找出或創建招式
        //     $move = Skill::firstOrCreate(['name' => $moveName]);

        //     // 關聯到寶可夢
        //     $race->skills()->attach($move->id);
        // }


        // }


        // 使用存到檔案的方式
  
        // $batchSize = 100;
        // $totalPokemons = 1011;
        // $pokemons = [];
        
        // // 讀取最後成功的位置
        // $lastIndex = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json') ?: 1;
        // // 讀取之前已存儲的所有寶可夢數據
        // $existingPokemons = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json');
        // if ($existingPokemons) {
        //     $pokemons = json_decode($existingPokemons, true);
        // }
        
        // for ($i = $lastIndex; $i <= $totalPokemons; $i += $batchSize) {
        //     $endRange = $i + $batchSize - 1;
        //     if ($endRange > $totalPokemons) {
        //         $endRange = $totalPokemons;
        //     }
        
        //     for ($j = $i; $j <= $endRange; $j++) {
        //         try {
        //             // ... 你原本的 API 請求和數據處理 ...
        //             $response1 = Http::get("https://pokeapi.co/api/v2/pokemon/$j");
        //             $pokemonDetail = $response1->json();
        //             $name = $pokemonDetail['name'];
        //             $photo = $pokemonDetail["sprites"]['front_default'];
        //             $moves = $pokemonDetail['moves'];
        //             $moveNames = [];
        
        //             // 取出此寶可夢所能學的所有的招式名稱
        //             foreach ($moves as $move) {
        //                 $moveNames[] = $move['move']['name'];
        //             }
        
        //             // 取得進化等級,這在pokemon-species的進化鏈裡面
        //             $pokemonData = Http::get("https://pokeapi.co/api/v2/pokemon-species/$j")->json();
        //             $getEvolutionChain = $pokemonData["evolution_chain"]['url'];
        //             $evolutionChain = Http::get($getEvolutionChain)->json();
        //             $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);
        
        //             $pokemons[] = [
        //                 'name' => $name,
        //                 'photo' => $photo,
        //                 'evolution_level' => $minLevel,
        //                 'skills' => $moveNames
        //             ];
                    
        //             // 每次成功請求一筆資料後，立即保存數據和更新成功的索引
        //             file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json', json_encode($pokemons, JSON_PRETTY_PRINT));
        //             file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json', $j + 1);
        
        //         } catch (\Exception $e) {
        //             // 處理或記錄異常，然後繼續下一筆
        //             continue;
        //         }
        //     }
        
        //     sleep(5);
        // }
        





        // 從檔案取出資料存入資料庫
        $pokemons = json_decode(file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json'), true);

        foreach ($pokemons as $pokemon) {
            $race = Race::create([
                'name' => $pokemon['name'],
                'photo' => $pokemon['photo'],
                'evolution_level' => $pokemon['evolution_level']
            ]);

            foreach ($pokemon['skills'] as $moveName) {
                $move = Skill::firstOrCreate(['name' => $moveName]);
                $race->skills()->attach($move->id);
            }
        }




        


       

        // 併發請求
        // $client = new Client();

        // $batchSize = 10;
        // $totalPokemons = 100;
        // $pokemons = [];

        // for ($i = 1; $i <= $totalPokemons; $i += $batchSize) {
        //     $requests = function () use ($i, $batchSize, $totalPokemons) {
        //         for ($j = 0; $j < $batchSize && ($i + $j) <= $totalPokemons; $j++) {
        //             yield new Request('GET', "https://pokeapi.co/api/v2/pokemon/" . ($i + $j));
        //         }
        //     };

        //     $responses = Pool::batch($client, $requests(), [
        //         'concurrency' => $batchSize,
        //         'fulfilled' => function ($response, $index) use (&$pokemons, $client) {
        //             $pokemonDetail = json_decode($response->getBody(), true);
        //             $name = $pokemonDetail['name'];
        //             $photo = $pokemonDetail["sprites"]['front_default'];
        //             $moves = $pokemonDetail['moves'];
        //             $moveNames = [];

        //             foreach ($moves as $move) {
        //                 $moveNames[] = $move['move']['name'];
        //             }

        //             $pokemonDataResponse = $client->get("https://pokeapi.co/api/v2/pokemon-species/{$pokemonDetail['id']}");
        //             $pokemonData = json_decode($pokemonDataResponse->getBody(), true);
        //             $getEvolutionChain = $pokemonData["evolution_chain"]['url'];
        //             $evolutionChainResponse = $client->get($getEvolutionChain);
        //             $evolutionChain = json_decode($evolutionChainResponse->getBody(), true);
        //             $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);

        //             $pokemons[] = [
        //                 'name' => $name,
        //                 'photo' => $photo,
        //                 'evolution_level' => $minLevel,
        //                 'skills' => $moveNames
        //             ];
        //         },
        //         'rejected' => function ($reason, $index) {
        //             // Log the error or handle the failed request.
        //             echo "Request $index failed: $reason\n";
        //         },
        //     ]);


        //     file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json', json_encode($pokemons, JSON_PRETTY_PRINT));
        // }

        // Please ensure you have the method `getEvolutionMinLevel` implemented in your class as per your previous code.

    }








    // 寶可夢進化鏈的結構是多為陣列,越高級的種族包得越裡面
    // 取得進化等級
    // public function getEvolutionMinLevel($chain, $pokemonName)
    // {
    //     // 如果是第一階段的寶可夢
    //     if ($chain['chain']['species']['name'] == $pokemonName) {
    //         // 查看它的進化細節,找到進化等級
    //         if (isset($chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'])) {
    //             return $chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'];
    //         }

    //         // 代表他不用進化
    //         return null;
    //     }
    //     return $this->searchEvolutionLevel($chain['chain'], $pokemonName);
    // }


    // public function searchEvolutionLevel($chain, $pokemonName)
    // {
    //     if ($chain['species']['name'] == $pokemonName) {
    //         if (isset($chain['evolves_to'][0]['evolution_details'][0]['min_level'])) {
    //             return $chain['evolves_to'][0]['evolution_details'][0]['min_level'];
    //         }
    //         // 可能代表此為最高級
    //         return null;
    //     }

    //     // 重複以上過程直到找到該寶可夢
    //     foreach ($chain['evolves_to'] as $nextChain) {
    //         $level = $this->searchEvolutionLevel($nextChain, $pokemonName);
    //         if ($level !== null) {
    //             return $level;
    //         }
    //     }

    //     return null;
    // }
}
