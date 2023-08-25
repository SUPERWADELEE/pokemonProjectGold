<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // dd('fuck');
        // 使用併發的方法
        //         $client = new Client();

        //         $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        //         $allPokemons = json_decode($response->getBody(), true)['results'];
        //         // dd($allPokemons);
        //         $urls = array_map(fn ($pokemon) => $pokemon['url'], $allPokemons);

        //         $batchSize = 20;
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
        //     $requests = function () use ($batch) {
        //         foreach ($batch as $url) {
        //             yield new LaravelRequest('GET', $url);
        //         }
        //     };
        
        //     Pool::batch($client, $requests(), [
        //         'concurrency' => $batchSize,
        //         'fulfilled' => function ($response, $index) use (&$responsesArr, &$globalIndex) {
        //             $responsesArr[$globalIndex + $index] = json_decode($response->getBody(), true);
        //         },
        //         'rejected' => function ($reason, $index) {
        //             error_log("Request $index failed: $reason");
        //         },
        //     ]);
        
        //     $globalIndex += $batchSize;
        // }
        
        // // 按原始顺序处理响应
        // ksort($responsesArr);  // 确保响应数组按键值排序
        // foreach ($responsesArr as $index => $pokemonDetail) {
        //     Race::create([
        //         'name' => $allPokemons[$index]['name'],
        //         'photo' => $pokemonDetail["sprites"]['front_default']
        //     ]);
        // }
        






        // $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        // $allPokemons = $response->json();
        // try {
        //     foreach ($allPokemons['results'] as $allPokemons) {
        //         $response = Http::get($allPokemons['url']);
        //         $pokemonDetail = $response->json();
        //         Race::create([
        //             'name' => $allPokemons['name'],
        //             'photo' => $pokemonDetail["sprites"]['front_default']
        //         ]);
        //     }
        // } catch (Expectation $e) {
        //     return response()->json([
        //         'error' => $e->getExceptionMessage()
        //     ]);
        // }



        // dd($allPokemons);
        // $names =[];


        #取得所有寶可夢名稱
        // $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');

        // $allPokemons = $response->json();
        // foreach($allPokemons['results'] as $allPokemons){
        //     // $names[]=$allPokemons['name'];
        //     if (isset($allPokemons['name'])) {
        //         // dd($allPokemons['name']);
        //         Race::create([
        //             'name' => $allPokemons['name']
        //         ]);
        //     }
        // }





        // foreach ($data['data']['pokemon_v2_naturename'] as $item) {
        //     if (isset($item['name'])) {
        //         Nature::create([
        //             'name' => $item['name']
        //         ]);
        //     }
        // }
        // $allPokemons = Pokemon::find(1);
        // // dd($allPokemons); 
        // return response()->json($allPokemons);
    }
}
