<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Race;

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
            'skills' => [
                'array',
                'min:1',
                'max:4',
                function ($attribute, $value, $fail) {
                    $race = Race::find($this->input('race_id'));
                    if (!$race) {
                        return $fail('The race_id is invalid.');
                    }
                    $allowedSkills = $race->skills->pluck('id')->toArray();

                    foreach ($value as $skillId) {
                        if (!in_array($skillId, $allowedSkills)) {
                            return $fail("The skill with ID {$skillId} is not allowed for this race.");
                        }
                    }
                }
            ]
        ];
    }
}


