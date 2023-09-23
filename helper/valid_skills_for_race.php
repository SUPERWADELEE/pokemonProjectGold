<?php
use App\Models\Race;

function validSkillsForRace($skills, Illuminate\Http\Request $request, Race $race) {
    $pokemonId = $request->input('race_id');
    $pokemon = $race->find($pokemonId);
    $allowedSkills = $pokemon->skills->pluck('id')->toArray();
    
    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    return true;
}
