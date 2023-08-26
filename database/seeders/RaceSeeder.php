<?php

namespace Database\Seeders;

use App\Models\Nature;
use App\Models\Race;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use Mockery\Expectation;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as LaravelRequest;
use Mockery\Exception;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // grapgh QL方法
        //         $client = new Client();

        //         // 修改你的查詢字符串以包含操作名稱
        //         $queryString = '
        //         query getPokemonNamesAndGenerations {
        //           pokemons: pokemon_v2_pokemon {
        //             name
        //           }
        //           generations: pokemon_v2_generation {
        //             name
        //           }
        //         }
        //         ';




        // $response = $client->post('https://beta.pokeapi.co/graphql/v1beta', [
        //     'headers' => [
        //         'content-type' => 'application/json',
        //         'accept' => '*/*'
        //     ],
        //     'json' => [
        //         'query' => $queryString,
        //         'variables' => null,
        //         'operationName' => 'getPokemonNamesAndGenerations'


        //   // 更新此處
        //     ]
        // ]);

        // $data = json_decode($response->getBody(), true);
        // dd($data);


        // dd('fuck');
        // 使用併發的方法
        //         $client = new Client();

        //         $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit=100');
        //         $allPokemons = json_decode($response->getBody(), true)['results'];
        //         // dd($allPokemons);
        //         $urls = array_map(fn ($pokemon) => $pokemon['url'], $allPokemons);

        //         $batchSize = 10;
        //         $urlBatches = array_chunk($urls, $batchSize);
        // // dd($urlBatches);
        //         foreach ($urlBatches as $batch) {
        //             $requests = function () use ($batch) {
        //                 foreach ($batch as $url) {
        //                     yield new LaravelRequest('GET', $url);
        //                 }
        //             };

        //             $responses = Pool::batch($client, $requests(), [
        //                 'concurrency' => $batchSize,
        //                 'fulfilled' => function ($response, $index) use ($allPokemons) {
        //                     $pokemonDetail = json_decode($response->getBody(), true);
        //                     // dd($pokemonDetail);
        //                     Race::create([
        //                         'name' => $allPokemons[$index]['name'],
        //                         'photo' => $pokemonDetail["sprites"]['front_default']
        //                     ]);
        //                 },
        //                 'rejected' => function ($reason, $index) {
        //                     error_log("Request $index failed: $reason");
        //                 },
        //             ]);
        //         }





        // $client = new Client();

        // $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit=100');
        // $allPokemons = json_decode($response->getBody(), true)['results'];
        // $urls = array_map(fn ($pokemon) => $pokemon['url'], $allPokemons);

        // $batchSize = 10;
        // $urlBatches = array_chunk($urls, $batchSize);

        // $responsesArr = [];  // 用于存储并发请求的结果

        // $globalIndex = 0;  // 全局索引，用于跟踪所有的请求

        // foreach ($urlBatches as $batch) {
        //     $localIndex = 0;  // 每个批次的局部索引
        //     $requests = function () use ($batch) {
        //         foreach ($batch as $url) {
        //             yield new LaravelRequest('GET', $url);
        //         }
        //     };

        //     Pool::batch($client, $requests(), [
        //         'concurrency' => $batchSize,
        //         'fulfilled' => function ($response, $index) use (&$responsesArr, &$globalIndex, &$localIndex) {
        //             $responsesArr[$globalIndex + $localIndex] = json_decode($response->getBody(), true);
        //             Race::create([
        //                 'name' => $responsesArr[$globalIndex + $localIndex]['name'],
        //                 'photo' => $responsesArr[$globalIndex + $localIndex]["sprites"]['front_default']
        //             ]);
        //             $localIndex++;
        //         },
        //         'rejected' => function ($reason, $index) {
        //             error_log("Request $index failed: $reason");
        //         },
        //     ]);

        //     $globalIndex += $batchSize;
        // }







        // // 取得所有寶可夢名稱及照片（一次一次打）
        // $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        // $allPokemonsArray = $response->json();

        // try {
        //     foreach ($allPokemonsArray['results'] as $pokemon) {
        //         $response = Http::get($pokemon['url']);
        //         $pokemonDetail = $response->json();
        //         // dd($pokemonDetail)


        //         Race::create([
        //             'name' => $pokemon['name'],
        //             'photo' => $pokemonDetail["sprites"]['front_default']
        //         ]);
        //     }
        // } catch (Exception $e) {
        //     echo "Error: " . $e->getMessage() . "\n";
        //     return;
        // }




        // dd($allPokemons);
        // $names =[];


        // 取得所有寶可夢的名字及進化等級（一次一次打）
        // $response = Http::get('https://pokeapi.co/api/v2/pokemon-species?limit=5');
        // $allPokemonsArray = $response->json();
        // // dd($allPokemonsArray);
        // try {
        //     foreach ($allPokemonsArray['results'] as $pokemon) {
        //         $response = Http::get($pokemon['url']);
        //         $pokemonDetail = $response->json();
        //         // dd($pokemonDetail["evolution_chain"]);
        //         $evolutionChain = $pokemonDetail["evolution_chain"]['url'];
        //         // dd($evolutionChain);
        //         $response = Http::get($evolutionChain);
        //         $evolutionChain = $response->json();
        //         // dd($evolutionChain['chain']['evolves_to'][0]['evolution_details'][0]['min_level']);

        //         $name = $pokemon['name'];
        //         // $name = 'ivysaur';
        //         // $name = 'iron-leaves';
        //         $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);
        //         // dd($minLevel);

        //         Race::create([
        //             'name' => $pokemon['name'],
        //             // 'photo' => $pokemonDetail["sprites"]['front_default']
        //             'evolution_level'=>$minLevel
        //         ]);
        //     }
        // } catch (Exception $e) {
        //     echo "Error: " . $e->getMessage() . "\n";
        //     return;
        // }






        // 取得寶可夢照片
        // 招式
        // $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=5');
        // $allPokemonsArray = $response->json();

        // try {
        //     foreach ($allPokemonsArray['results'] as $pokemon) {
        //         $response = Http::get($pokemon['url']);
        //         $pokemonDetail = $response->json();
        //         // dd($pokemonDetail)
        //         $pokemonDetail["sprites"]['front_default'];

        //         $moves = $pokemonDetail['moves'];

        //         // $moveNames = [];

        //         // foreach ($moves as $moveDetail) {
        //         //     $moveNames[] = $moveDetail['move']['name'];
        //         // }

        //         $pokemonModel = Race::create([
        //             'name' => $pokemon['name'],
        //             'photo' => $pokemonDetail["sprites"]['front_default']
        //         ]);
        //         foreach ($moves as $moveName) {
        //             // 先找出或創建招式
        //             // dd($moveName['move']['name']);
        //             $move = Skill::firstOrCreate(['name' => $moveName]);

        //             // 關聯到寶可夢
        //             $pokemonModel->skills()->attach($move->id);
        //         }


        //         // dd($moveNames);  // 這會打印所有技能名稱的陣列

        //         // dd($pokemonDetail['moves'][0]['move']['name']);


        //     }
        // } catch (Exception $e) {
        //     echo "Error: " . $e->getMessage() . "\n";
        //     return;
        // }




        // 姓名、照片、進化等級、技能一次全部拿,全部存

        $batchSize = 1; // 可以根據需要調整
        $totalPokemons = 1011; // 根據你的實際數量調整

        for ($i = 1; $i < $totalPokemons; $i += $batchSize) {


            // 取得名稱、照片、招式
            $response1 = Http::get("https://pokeapi.co/api/v2/pokemon/$i");
            $pokemonDetail = $response1->json();
            // dd($allPokemonsArray);
            $name = $pokemonDetail['name'];
            // dd($name);
            $photo = $pokemonDetail["sprites"]['front_default'];
            $moves = $pokemonDetail['moves'];
            $moveNames = [];

            // 取出所有的招式名稱
            foreach ($moves as $move) {
                $moveNames[] = $move['move']['name'];
                // dd($moveNames);
            }



            // 取得進化等級
            $response2 = Http::get("https://pokeapi.co/api/v2/pokemon-species/$i");
            $response3 = $response2->json();
            $getEvolutionChain = $response3["evolution_chain"]['url'];
            $response4 = Http::get($getEvolutionChain);
            $evolutionChain = $response4->json();
            // $name = 'ivysaur';
            $minLevel = $this->getEvolutionMinLevel($evolutionChain, $name);
            // dd($minLevel);
            $race = Race::create([
                'name' => $name,
                'photo' => $photo,
                'evolution_level' => $minLevel
            ]);


            foreach ($moveNames as $moveName) {
                // 先找出或創建招式
                $move = Skill::firstOrCreate(['name' => $moveName]);
            
                // 關聯到寶可夢
                $race->skills()->attach($move->id);
            }
            

           

            // dd($moveNames);


        }
    }














    //     public function getEvolutionMinLevel($chain, $pokemonName) {
    //         if ($chain['chain']['species']['name'] == $pokemonName) {
    //             // dd('fuck');
    //             // 這個寶可夢是當前階段的寶可夢，返回他進化到下一階段所需的等級
    //             if(isset($chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'])) {

    //                 return $chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'];
    //             }
    //             return null; // 如果這個寶可夢沒有進化到下一階段的等級，返回null
    //         }

    //         // 如果不是當前階段的寶可夢，繼續查找下一個進化階段
    //         foreach ($chain['chain']['evolves_to'] as $nextChain) {
    //             // dd('fuck');
    //             $level = $this->getEvolutionMinLevel($nextChain, $pokemonName);
    //             if ($level !== null) {
    //                 return $level;
    //             }
    //         }

    //         return null; // 沒有找到對應的寶可夢進化等級
    // }

    public function getEvolutionMinLevel($chain, $pokemonName)
    {
        // 如果是第一階段的寶可夢
        if ($chain['chain']['species']['name'] == $pokemonName) {
            // 查看它的進化細節
            if (isset($chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'])) {
                return $chain['chain']['evolves_to'][0]['evolution_details'][0]['min_level'];
            }
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
            return null;
        }

        foreach ($chain['evolves_to'] as $nextChain) {
            $level = $this->searchEvolutionLevel($nextChain, $pokemonName);
            if ($level !== null) {
                return $level;
            }
        }

        return null;
    }
}
