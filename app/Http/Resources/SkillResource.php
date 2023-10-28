<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    /**
     * 將資源轉換為一個陣列。
     *
     * 此方法將技能資源轉換為一個陣列，以便可以以JSON格式返回。
     *
     * @param Request $request
     * @return array<string, mixed> 返回一個包含技能ID和名字的陣列。
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

        ];
    }
}
