<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Race;
use App\Rules\SkillJudgment;

class UpdatePokemonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;  // 如果您想讓所有使用者都能發送此請求，則設為 true。否則您需要定義認證邏輯。
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'race_id' => 'integer|exists:races,id',
            'ability_id' => 'integer|exists:abilities,id',
            'nature_id' => 'integer|exists:natures,id',
            'level' => 'integer|min:1|max:100',
            'skills' => ['required','array','min:1','max:4', new SkillJudgment()]
        ];
    }
}


