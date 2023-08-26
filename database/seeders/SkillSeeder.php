<?php

namespace Database\Seeders;

use App\Models\Skill;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as LaravelRequest;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://pokeapi.co/api/v2/move?limit=918');
        $allSkillsArray = $response->json();

        $skillsToInsert = [];  // 創建一個空陣列，用於存放需要插入的技能

        foreach ($allSkillsArray['results'] as $skills) {
            $skillsToInsert[] = [
                'name' => $skills['name'],
                'created_at' => now(),  // 為了完整性，可能需要添加 created_at 和 updated_at 字段
                'updated_at' => now()
            ];
        }

        // 一次性插入所有技能到資料庫中
        Skill::insert($skillsToInsert);



        // 使用併發的方法
    //     $client = new Client();

    //     $response = $client->get('https://pokeapi.co/api/v2/move?limit=10');
    //     $allSkills = json_decode($response->getBody(), true)['results'];
    //     // dd($allPokemons);
    //     $urls = array_map(fn ($pokemon) => $pokemon['url'], $allSkills);

    //     $batchSize = 10;
    //     $urlBatches = array_chunk($urls, $batchSize);
    //     // dd($urlBatches);
    //     foreach ($urlBatches as $batch) {
    //         $requests = function () use ($batch) {
    //             foreach ($batch as $url) {
    //                 yield new LaravelRequest('GET', $url);
    //             }
    //         };

    //         $responses = Pool::batch($client, $requests(), [
    //             'concurrency' => $batchSize,
    //             'fulfilled' => function ($response, $index) use ($allSkills) {
    //                 $pokemonDetail = json_decode($response->getBody(), true);
    //                 // dd($pokemonDetail);
    //                 Race::create([
    //                     'name' => $allSkills[$index]['name'],

    //                 ]);
    //             },
    //             'rejected' => function ($reason, $index) {
    //                 error_log("Request $index failed: $reason");
    //             },
    //         ]);
    //     }
    }
}
