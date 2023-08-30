<?php

namespace App\Http\Resources;

use App\Models\Race;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 如果 $this->skills 是一個 id 陣列
        $selectedSkills = Skill::whereIn('id', $this->skills)->get();
        $skillNames = $selectedSkills->pluck('name')->toArray();
    
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race' => $this->race->name, // 直接從已載入的 race 關聯獲取名稱
            'ability' => $this->ability->name,
            'nature' => $this->nature->name,
            'photo' => $this->race->photo, // 直接從已載入的 race 關聯獲取照片
            'skills' => $skillNames
        ];
    }
    
}
