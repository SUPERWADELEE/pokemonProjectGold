<?php

namespace Database\Seeders;

use App\Models\Nature;
use App\Models\Race;
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







        // 取得所有寶可夢名稱及照片（一次一次打）
        $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1281');
        $allPokemonsArray = $response->json();

        try {
            foreach ($allPokemonsArray['results'] as $pokemon) {
                $response = Http::get($pokemon['url']);
                $pokemonDetail = $response->json();
                // dd($pokemonDetail)
                Race::create([
                    'name' => $pokemon['name'],
                    'photo' => $pokemonDetail["sprites"]['front_default']
                ]);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return;
        }




        // dd($allPokemons);
        // $names =[];



    }
}
