<?php

/**
     * 檢查給定的技能是否為該種族允許的技能。
     *
     * @param array $skills 要驗證的技能ID集合。
     * @param Race $race 要檢查的寶可夢種族。
     * @return bool 返回技能是否有效的布爾值。
     */
function validSkillsForRace($skills, $race) {
    $allowedSkills = $race->skills->pluck('id')->toArray();
    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    
    return true;
}



