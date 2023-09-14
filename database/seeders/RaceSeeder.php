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
            // 取出此寶可夢所能學的所有的招式名稱
            foreach ($moves as $moves) {
                $moveNames[] = $moves['move']['name'];
                // dd($moveNames);
            }

            // 取得進化等級,這在pokemon-species的進化鏈裡面
            
            $pokemonData = Http::get("https://pokeapi.co/api/v2/pokemon-species/$i")->json();
            $getEvolutionChain = $pokemonData["evolution_chain"]['url'];
            $evolutionChain = Http::get($getEvolutionChain)->json();
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
        }
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
