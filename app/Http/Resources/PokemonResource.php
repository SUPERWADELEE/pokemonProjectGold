<?php

namespace App\Http\Resources;

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
        // 先取得此寶可夢的技能ID列表
        $inputSkills = $this->skills;

        // 使用whereIn方法從race.skills中篩選出對應的技能
        $selectedSkills = $this->race->skills->whereIn('id', $inputSkills);
        // dd($selectedSkills);
        // 獲取技能名稱
        $skillNames = $selectedSkills->pluck('name')->toArray();
        // dd($this->name);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'race' => $this->race->name,
            'ability' => $this->ability->name,
            'nature' => $this->nature->name,
            'skills' => $skillNames
            // 你還可以加入其他的欄位和邏輯...
        ];
    }
}
