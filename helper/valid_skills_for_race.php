<?php
use App\Models\Race;

function validSkillsForRace($skills) {
    $pokemonId = request()->input('race_id');
    $pokemon = Race::find($pokemonId);
    $allowedSkills = $pokemon->skills->pluck('id')->toArray();
    
    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    return true;
}
