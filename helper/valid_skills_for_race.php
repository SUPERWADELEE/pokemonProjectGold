<?php
use App\Models\Race;

function valid_skills_for_race($skills) {
    // 先取得當前輸入的種族id
    
    $pokemonId = request()->input('race_id');
    // 找到相關的種族
    $pokemon = Race::find($pokemonId);
    // 找到該種族的所有技能id組成陣列
    $allowedSkills = $pokemon->skills->pluck('id')->toArray();
    
    // 只要輸入的技能不存在該種族能學的技能,噴錯
    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    
    return true;
}
