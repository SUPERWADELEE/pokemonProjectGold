<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'amount' => $this->quantity,

            'current_price' => $this->whenloaded('race', function(){
                return $this->race->price;
            }),

            'race_name' => $this->whenloaded('race', function(){
                return $this->race->name;
            }),
            'race_photo' => $this->whenloaded('race', function(){
                return $this->race->photo;
            })

        ];
    
        
    }
}
