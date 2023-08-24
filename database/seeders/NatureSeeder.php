<?php

namespace Database\Seeders;

use App\Models\Nature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(__DIR__ . '/allNatures.txt');

        $data = json_decode($json, true);

        foreach ($data['data']['pokemon_v2_naturename'] as $item) {
            if (isset($item['name'])) {
                Nature::create([
                    'name' => $item['name']
                ]);
            }
        }
    }
}
