<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Nature extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'name', 
        
        // ... 其他允許的屬性 ...
    ];

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     $result = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

    //     if (! $result) {
    //         // 拋出您自己的異常或者使用 Laravel 預設的 ModelNotFoundException
    //         throw new ModelNotFoundException("Page not found.");
    //     }

    //     return $result;
    // }
}
