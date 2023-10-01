<?php
use App\Models\Race;
use Illuminate\Http\Request;

function validSkillsForRace($skills, $race) {
    $allowedSkills = $race->skills->pluck('id')->toArray();


    // // TODO這個命名規則也要改
// function validSkillsForRace($skills) {
//     // 先取得當前輸入的種族id
//     $pokemonId = request()->input('race_id');
//     // 找到相關的種族
//     $pokemon = Race::find($pokemonId);
//     // 找到該種族的所有技能id組成陣列
//     // $allowedSkills = $pokemon->skills->pluck('id')->toArray();
//     $allowedSkills = $pokemon->skills()->select('skills.id')->pluck('id')->toArray();
//     // dd($allowedSkills);
// // 
//     // $pokemon->skills -> collection Object
//     // $pokemon->skills() -> belongsToMany Object (Query Builder)
//     // dd($skills);
//     // dd($allowedSkills);
//     // 只要輸入的技能不存在該種族能學的技能,噴錯
//     // 焦急連擊
    
    // if(!array_intersect($skills, $allowedSkills)){
    //     return false;
    // }
    // return true;

    // dd($skills);

    foreach ($skills as $skillId) {
        if (!in_array($skillId, $allowedSkills)) {
            return false;
        }
    }
    
    return true;
}



