<?php

namespace Database\Seeders;

use App\Models\Nature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $response = Http::get('https://pokeapi.co/api/v2/pokemon');
        
        dd($response);
        $data = $response->json();
        

        foreach ($data['data']['pokemon_v2_naturename'] as $item) {
            if (isset($item['name'])) {
                Nature::create([
                    'name' => $item['name']
                ]);
            }
        }
    }
}
