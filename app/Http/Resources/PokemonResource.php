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

        // TODO設定可能cache可以一個小時後刪除
        // TODOcache可以研究存在哪裡？
        $minutes = 60; // 設定 cache 60 分鐘後過期
        $allSkills = Cache::remember('all_skills', $minutes, function () {
            return Skill::all();
        });
        $allSkillsArray = $allSkills->pluck('name', 'id')->toArray();

        // 取得 $this->skills 中指定的技能名稱
        $selectedSkillNames = array_intersect_key($allSkillsArray, array_flip($this->skills));
        // 如果需要，將其重新整理為索引陣列
        $selectedSkillNames = array_values($selectedSkillNames);


        // $skillNames = collect($this->skills)->map(function ($skillId) use ($allSkills) {
        //     return $allSkills[$skillId]->name ?? null;
        // })->filter()->toArray();

        // TODO whenloaded用法可以用可以再去理解
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race_id'=> $this->race_id,
            // 'race' => $this->whenLoaded('race', $this->race),
            // 'race' => $this->whenLoaded('race', $this->race->name),
            'race' => $this->whenLoaded('race', function () {
                return $this->race->name;
            }),
            'photo' => $this->whenLoaded('race', function () {
                return $this->race->photo;
            }),
            // 'photo' => $this->whenLoaded('race', function () {
            //     return $this->race->photo;
            // }),
            'ability' => $this->whenLoaded('ability', function () {
                return $this->ability->name;
            }),
            'nature' => $this->whenLoaded('nature', function () {
                return $this->nature->name;
            }),
            // 'nature' => $this->whenLoaded('nature', $this->nature->name),
            'skills' => $selectedSkillNames,
            'host' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
            

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

