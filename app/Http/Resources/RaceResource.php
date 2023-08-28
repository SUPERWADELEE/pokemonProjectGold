<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'evolution_level' => $this->evolution_level,
            'skills' => SkillResource::collection($this->skills)
            // 你還可以加入其他的欄位和邏輯...
        ];
    }
}
