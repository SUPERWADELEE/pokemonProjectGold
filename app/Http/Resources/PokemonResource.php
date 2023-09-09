<?php

namespace App\Http\Resources;

use App\Models\Race;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // todo: coollection wherein
        // 如果 $this->skills 是一個 id 陣列,去找skill表中,尋找這個陣列存在的id的所有資料
        // $selectedSkills = Skill::whereIn('id', $this->skills)->get();
        // 從這個skill資料的集合中,取出所有的名稱組成陣列
        // $skillNames = $selectedSkills->pluck('name')->toArray();
   
        // dd($this['pokemon']);
        
        
        // dd($this->ability->name);
        // 將collection中的每一個object都跑一遍
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race' => $this->whenLoaded('race', $this->race->name),
            'photo' => $this->whenLoaded('race', $this->race->photo),
            'ability' => $this->whenLoaded('ability', $this->ability->name),
            'nature' => $this->whenLoaded('nature', function() {
                return $this->nature->name;
            }),
            // 'nature' => $this->whenLoaded('nature', $this->nature->name),
            'skills' => $this->whenLoaded('race', $this->getRaceSkills()),
            'host' => $this->whenLoaded('user', $this->user->name),
        ];


    
        // return $data;
        
    }

    protected function getRaceSkills()
    {
        return $this->race->skills->whereIn('id', $this->skills)->pluck('name');
    }
    // public function skillNames(){
    //     return $skillNames = $selectedSkills->pluck('name')->toArray();
    // }
    
}
