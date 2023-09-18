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
// use Illuminate\Http\Client\Pool;




class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {

        // dd('fuck');
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
            // dd('fuck');
        $batchSize = 1;
        $totalPokemons = 2;
        $pokemons = [];

        // 讀取最後成功的位置
        $lastIndex = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json') ?: 1;
        // 讀取之前已存儲的所有寶可夢數據
        $existingPokemons = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json');
        if ($existingPokemons) {
            $pokemons = json_decode($existingPokemons, true);
        }


        for ($i = $lastIndex; $i <= $totalPokemons; $i += $batchSize) {
            $endRange = $i + $batchSize - 1;
            if ($endRange > $totalPokemons) {
                $endRange = $totalPokemons;
            }

            
            for ($j = $i; $j <= $endRange; $j++) {
                try {
                    // ... 你原本的 API 請求和數據處理 ...
                    $response1 = Http::get("https://pokeapi.co/api/v2/pokemon/$j");
                    $pokemonDetail = $response1->json();
                    $name = $pokemonDetail['name'];
                    $photo = $pokemonDetail["sprites"]['front_default'];
                    $moves = $pokemonDetail['moves'];
                    $moveNames = [];

                    // 取出此寶可夢所能學的所有的招式名稱
                    foreach ($moves as $move) {
                        $moveNames[] = $move['move']['name'];
                    }

                    // 取得進化等級,這在pokemon-species的進化鏈裡面
                    $pokemonData = Http::get("https://pokeapi.co/api/v2/pokemon-species/$j")->json();
                    $getEvolutionChain = $pokemonData["evolution_chain"]['url'];
                    $evolutionChain = Http::get($getEvolutionChain)->json();
                    $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);

                    $pokemons[] = [
                        'name' => $name,
                        'photo' => $photo,
                        'evolution_level' => $minLevel,
                        'skills' => $moveNames
                    ];

                    // dd($pokemons);
                    // 每次成功請求一筆資料後，立即保存數據和更新成功的索引
                    $result1 = file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json', json_encode($pokemons, JSON_PRETTY_PRINT));
                    $result2 = file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json', $j + 1);

                    if ($result1 === false || $result2 === false) {
                        // 寫入失敗，可以在這裡記錄或拋出異常
                        throw new \Exception("Failed to write to file");
                    }
                } catch (\Exception $e) {
                    // 處理或記錄異常，然後繼續下一筆
                    continue;
                }
            }

            sleep(5);
        }





// 使用病發方法（未解決）
        // $totalPokemons = 4;
        // $batchSize = 2;
        // $pokemons = [];
        // $lastIndex = (int)@file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json') ?: 1;


        // // 讀取最後成功的位置
        // $lastIndex = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json') ?: 1;
        // // 讀取之前已存儲的所有寶可夢數據
        // $existingPokemons = @file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemonsTest.json');
        // if ($existingPokemons) {
        //     $pokemons = json_decode($existingPokemons, true);
        // }

        // $client = new Client();
        // $responses = [];

        // $requests = function ($totalPokemons, $lastIndex) {
        //     $uri = 'https://pokeapi.co/api/v2/pokemon/';
        //     for ($i = $lastIndex; $i <= $totalPokemons; $i++) {
        //         yield new Request('GET', $uri . $i);
        //     }
        // };

        // $handleSuccessfulResponse = function ($response, $index) use (&$responses, $client) {
        //     // $handleSuccessfulResponse = function ($response, $index) use (&$responses, $client) {
        //         $lastIndex = $index;   
        //     $pokemonDetail = json_decode($response->getBody()->getContents(), true);
        //     // dd($index);
        //     $name = $pokemonDetail['name'];
        //     $photo = $pokemonDetail["sprites"]['front_default'];
        //     $moves = $pokemonDetail['moves'];
        //     $moveNames = [];

        //     foreach ($moves as $move) {
        //         $moveNames[] = $move['move']['name'];
        //     }

        //     // 取得進化等級,這在pokemon-species的進化鏈裡面
        //     // dd($index);
        //     $pokemonSpeciesData = $client->request('GET', "https://pokeapi.co/api/v2/pokemon-species/{$index}")->getBody()->getContents();
        //     $pokemonSpecies = json_decode($pokemonSpeciesData, true);
        //     $getEvolutionChain = $pokemonSpecies["evolution_chain"]['url'];

        //     $evolutionChainData = $client->request('GET', $getEvolutionChain)->getBody()->getContents();
        //     $evolutionChain = json_decode($evolutionChainData, true);

        //     // 假設 $this->getEvolutionMinLevel 是你之前的進化鏈處理方法
        //     $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);

        //     // 把最終的數據結果存到 $responses

        //     $responses[$index] = [
        //         'name' => $name,
        //         'photo' => $photo,
        //         'skills' => $moveNames,
        //         'evolution_level' => $minLevel
        //     ];
        //     $pokemons[$index] = $responses[$index];  // 添加到$pokemons数组
        // };


        // $pool = new Pool($client, $requests($totalPokemons, $lastIndex), [
        //     'concurrency' => $batchSize,
        //     'fulfilled' => $handleSuccessfulResponse,
        //     'rejected' => function ($reason, $index) use ($client, $handleSuccessfulResponse) {
        //         // 你的失败处理逻辑
        //         // dd($index);
        //         // dd($index);
        //         $uri = 'https://pokeapi.co/api/v2/pokemon/' . $index;
        //         $retryRequest = new Request('GET', $uri);
        //         $client->sendAsync($retryRequest)->then(function ($response) use ($index, &$responses, $handleSuccessfulResponse) {
        //             $handleSuccessfulResponse($response, $index);
        //         });
        //     }
        // ]);

        // $promise = $pool->promise();
        // $promise->wait();

        // // 当所有请求完成后，保存 $lastIndex
        // file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/index.json', $totalPokemons);
        // file_put_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemonsTest.json', json_encode($pokemons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));



        // $totalPokemons = 4;
        // $batchSize = 2;
        // $delayTime = 1000;
        // $client = new Client();
        // $responses = [];

        // $requests = function ($totalPokemons) {
        //     $uri = 'https://pokeapi.co/api/v2/pokemon/';
        //     for ($i = 1; $i <= $totalPokemons; $i++) {

        //         yield new Request('GET', $uri . $i);
        //     }
        // };

        // $handleSuccessfulResponse = function ($response, $index) use (&$responses, $client) {
        //     $pokemonDetail = json_decode($response->getBody()->getContents(), true);
        //     dd($index);
        //     $name = $pokemonDetail['name'];
        //     $photo = $pokemonDetail["sprites"]['front_default'];
        //     $moves = $pokemonDetail['moves'];
        //     $moveNames = [];

        //     foreach ($moves as $move) {
        //         $moveNames[] = $move['move']['name'];
        //     }

        //     // 取得進化等級,這在pokemon-species的進化鏈裡面
        //     // dd($index);
        //     $pokemonSpeciesData = $client->request('GET', "https://pokeapi.co/api/v2/pokemon-species/{$index}")->getBody()->getContents();
        //     $pokemonSpecies = json_decode($pokemonSpeciesData, true);
        //     $getEvolutionChain = $pokemonSpecies["evolution_chain"]['url'];

        //     $evolutionChainData = $client->request('GET', $getEvolutionChain)->getBody()->getContents();
        //     $evolutionChain = json_decode($evolutionChainData, true);

        //     // 假設 $this->getEvolutionMinLevel 是你之前的進化鏈處理方法
        //     $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);

        //     // 把最終的數據結果存到 $responses
        //     $responses[$index] = [
        //         'name' => $name,
        //         'photo' => $photo,
        //         'skills' => $moveNames,
        //         'evolution_level' => $minLevel
        //     ];
        // };


        // $pool = new Pool($client, $requests($totalPokemons), [
        //     'concurrency' => $batchSize,
        //     'delay' => $delayTime,
        //     'fulfilled' => $handleSuccessfulResponse, // Step 2: 使用 $handleSuccessfulResponse
        //     'rejected' => function ($reason, $index) use ($client, $handleSuccessfulResponse) { // Step 3: 通過 use 傳入
        //         // dd($index);
        //         $uri = 'https://pokeapi.co/api/v2/pokemon/' . $index;
        //         $retryRequest = new Request('GET', $uri);
        //         $client->sendAsync($retryRequest)->then(function ($response) use ($index, &$responses, $handleSuccessfulResponse) {
        //             $handleSuccessfulResponse($response, $index);
        //         });
        //     }
        // ]);

        // $promise = $pool->promise();
        // $promise->wait();

        // ksort($responses);

        // $jsonData = json_encode($responses, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使輸出的 JSON 格式化，更易於閱讀
        // file_put_contents('pokemonsTest.json', $jsonData);






        //    laravel的併發方法 
        // $totalPokemons = 4;

        // $responses = Http::pool(function (Pool $pool) use ($totalPokemons) {
        //     $uri = 'https://pokeapi.co/api/v2/pokemon/';
        //     $requests = [];
        //     for ($i = 1; $i <= $totalPokemons; $i++) {
        //         $requests[] = $pool->get($uri . $i);
        //     }
        //     return $requests;
        // });

        // $finalResponses = [];

        // foreach ($responses as $index => $response) {
        //     if ($response->successful()) {
        //         $pokemonDetail = $response->json();

        //         $name = $pokemonDetail['name'];
        //         $photo = $pokemonDetail["sprites"]['front_default'];
        //         $moves = $pokemonDetail['moves'];
        //         $moveNames = [];

        //         foreach ($moves as $move) {
        //             $moveNames[] = $move['move']['name'];
        //         }

        //         // 取得進化等級,這在pokemon-species的進化鏈裡面
        //         $pokemonSpecies = Http::get("https://pokeapi.co/api/v2/pokemon-species/{$index}")->json();
        //         $getEvolutionChain = $pokemonSpecies["evolution_chain"]['url'];

        //         $evolutionChain = Http::get($getEvolutionChain)->json();

        //         // 假設 $this->getEvolutionMinLevel 是你之前的進化鏈處理方法
        //         $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);

        //         $finalResponses[$index] = [
        //             'name' => $name,
        //             'photo' => $photo,
        //             'skills' => $moveNames,
        //             'evolution_level' => $minLevel
        //         ];
        //     }
        // }

        // ksort($finalResponses);

        // $jsonData = json_encode($finalResponses, JSON_PRETTY_PRINT); // 使用 JSON_PRETTY_PRINT 使輸出的 JSON 格式化，更易於閱讀
        // file_put_contents('pokemonsTest.json', $jsonData);









        // 從檔案取出資料存入資料庫
        // $pokemons = json_decode(file_get_contents('/Users/liweide/laravel/pokemon/database/seeders/pokemons.json'), true);

        // foreach ($pokemons as $pokemon) {
        //     $race = Race::create([
        //         'name' => $pokemon['name'],
        //         'photo' => $pokemon['photo'],
        //         'evolution_level' => $pokemon['evolution_level']
        //     ]);

        //     foreach ($pokemon['skills'] as $moveName) {
        //         $move = Skill::firstOrCreate(['name' => $moveName]);
        //         $race->skills()->attach($move->id);
        //     }
        // }








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
    public function getEvolutionMinLevel($chain, $pokemonName)
    {
        // 如果是第一階段的寶可夢
        if ($chain['chain']['species']['name'] == $pokemonName) {
            // 查看它的進化細節,找到進化等級
            if (isset($chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'])) {
                return $chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'];
            }

            // 代表他不用進化
            return null;
        }
        return $this->searchEvolutionLevel($chain['chain'], $pokemonName);
    }


    public function searchEvolutionLevel($chain, $pokemonName)
    {
        if ($chain['species']['name'] == $pokemonName) {
            if (isset($chain['evolves_to'][0]['evolution_details'][0]['min_level'])) {
                return $chain['evolves_to'][0]['evolution_details'][0]['min_level'];
            }
            // 可能代表此為最高級
            return null;
        }

        // 重複以上過程直到找到該寶可夢
        foreach ($chain['evolves_to'] as $nextChain) {
            $level = $this->searchEvolutionLevel($nextChain, $pokemonName);
            if ($level !== null) {
                return $level;
            }
        }

        return null;
    }
}
