<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        dd('fuck');
        $user = JWTAuth::parseToken()->authenticate();
        return [
            
                'userPhoto' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // "sometimes" 代表此欄位為可選
                'name' => 'sometimes|required|max:255', // "sometimes" 代表此欄位為可選
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id // "sometimes" 代表此欄位為可選
            
        ];
    }
}
