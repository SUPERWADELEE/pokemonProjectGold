<?php

namespace App\Http\Resources;

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
        $selectedSkills = Skill::whereIn('id', $this->skills)->get();

        // 獲取技能名稱
        $skillNames = $selectedSkills->pluck('name')->toArray();
        
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
