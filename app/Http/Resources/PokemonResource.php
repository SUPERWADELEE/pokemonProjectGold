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
        // dd('fuck');
        // dd($this->skills);
        // 如果 $this->skills 是一個 id 陣列,去找skill表中,尋找這個陣列存在的id的所有資料
        $selectedSkills = Skill::whereIn('id', $this->skills)->get();
        // 從這個skill資料的集合中,取出所有的名稱組成陣列
        // dd('fuck');
        $skillNames = $selectedSkills->pluck('name')->toArray();
   
        
        // dd($this->id);
        // 將collection中的每一個object都跑一遍
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race' => $this->race->name, // 直接從已載入的 race 關聯獲取名稱
            'ability' => $this->ability->name,
            'nature' => $this->nature->name,
            'photo' => $this->race->photo, // 直接從已載入的 race 關聯獲取照片
            'skills' => $skillNames,
            'host' => $this->user->name
            
         
        ];
    }
    
}
