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
     * Get custom attributes for body parameters.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'The name of the pokemon.',
                'example' => 'Pikachu',
                'required' => false,  // 因為在rules中是可選的
                'type' => 'string'
            ],
            'race_id' => [
                'description' => 'The ID of the race for the pokemon.',
                'example' => 1,
                'required' => false,
                'type' => 'integer'
            ],
            'ability_id' => [
                'description' => 'The ID of the ability for the pokemon.',
                'example' => 1,
                'required' => false,
                'type' => 'integer'
            ],
            'nature_id' => [
                'description' => 'The ID of the nature for the pokemon.',
                'example' => 1,
                'required' => false,
                'type' => 'integer'
            ],
            'level' => [
                'description' => 'The level for the pokemon.',
                'example' => 1,
                'required' => false,
                'type' => 'integer'
            ],
            'skills' => [
                'description' => 'The ID of the skills for the pokemon.',
                'example' => 1,
                'required' => false,
                'type' => 'integer'
            ],
            // ... 其他參數 ...
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // dd('fuck');
        return [
            'name' => 'string|max:15|unique:pokemons,name',
            'race_id' => 'integer|exists:races,id',
            'ability_id' => 'integer|exists:abilities,id',
            'nature_id' => 'integer|exists:natures,id',
            'level' => 'integer|min:1|max:100', //. $miniEvolutionLevel,
            'skills' => 'sometimes|array|min:1|max:4',
            'skills.*' => 'sometimes|exists:skills,id'

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // 如果沒有提供任何參數，則添加一個錯誤。
            if (empty($this->all())) {
                $validator->errors()->add('parameters', 'At least one parameter is required.');
                return;
            }
            if (is_null($this->skills)) {
                return true;  // 如果沒有提供技能，則直接返回true
            }
            $raceId = $this->input('race_id'); // 假设 race_id 是在请求中的一个字段
            $race = Race::find($raceId);
            // 在這裡做了一個skills的額外驗證,確認輸入的skill是否是該種族可以學的
            if (!validSkillsForRace($this->skills, request(), $race )) {
                $validator->errors()->add('skills', 'The skill is not allowed for this race.');
            }
        });
    }
}
