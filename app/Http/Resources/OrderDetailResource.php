<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'race_name' => $this->race->name,
            'quantity'=>$this->quantity,
            'unit_price'=>$this->unit_price,
            'subtotal_price'=>$this->subtotal_price
        ];
    }
}
