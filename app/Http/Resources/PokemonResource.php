<?php

namespace App\Http\Resources;

use App\Models\Race;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 取得技能id對應的名稱

        // : coollection wherein
        // 如果 $this->skills 是一個 id 陣列,去找skill表中,尋找這個陣列存在的id的所有資料
        // $selectedSkills = Skill::whereIn('id', $this->skills)->get();
        // 從這個skill資料的集合中,取出所有的名稱組成陣列
        // $skillNames = $selectedSkills->pluck('name')->toArray();

        // dd($this['pokemon']);
        // dd($this->skills);
        // dd($skillNames = collect($this->skills)->pluck('skill_id'));
        // dd($this->ability->name);
        // 將collection中的每一個object都跑一遍
        // dd($this->race->name);
        // 從本地快取中取得所有技能，這將確保僅執行一次DB查詢
        $allSkills = Cache::rememberForever('all_skills', function () {
            return Skill::all();
        });
        $allSkillsArray = $allSkills->pluck('name', 'id')->toArray();
    // dd($allSkillsArray);    
    // dd($this->skills);

        // 取得 $this->skills 中指定的技能名稱
        $selectedSkillNames = array_intersect_key($allSkillsArray, array_flip($this->skills));
        // dd($selectedSkillNames);    
        // 如果需要，將其重新整理為索引陣列
        $selectedSkillNames = array_values($selectedSkillNames);


        // $skillNames = collect($this->skills)->map(function ($skillId) use ($allSkills) {
        //     return $allSkills[$skillId]->name ?? null;
        // })->filter()->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race' => $this->whenLoaded('race', $this->race->name),
            'photo' => $this->whenLoaded('race', $this->race->photo),
            'ability' => $this->whenLoaded('ability', $this->ability->name),
            'nature' => $this->whenLoaded('nature', function () {
                return $this->nature->name;
            }),
            // 'nature' => $this->whenLoaded('nature', $this->nature->name),
            'skills' => $selectedSkillNames,
            'host' => $this->whenLoaded('user', $this->user->name),
        ];



        // return $data;

    }

    // protected function getRaceSkills()
    // {
    //     return $this->race->skills->whereIn('id', $this->skills)->pluck('name');
    // }
    // public function skillNames(){
    //     return $skillNames = $selectedSkills->pluck('name')->toArray();
    // }

}
