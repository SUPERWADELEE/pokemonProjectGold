<?php
use App\Models\Race;
use Illuminate\Http\Request;

function validSkillsForRace($skills, $race) {
    $allowedSkills = $race->skills->pluck('id')->toArray();
    
    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    return true;
}



