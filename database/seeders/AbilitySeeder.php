<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $json = file_get_contents(__DIR__ . '/allAbilities.txt');

        dd($json);
        $data = json_decode($json, true);
        

        foreach ($data['data']['pokemon_v2_ability'] as $item) {
            if (isset($item['pokemon_v2_abilitynames'][0]['name'])) {
                Ability::create([
                    'name' => $item['pokemon_v2_abilitynames'][0]['name']
                ]);
            }
        }
    }
}
