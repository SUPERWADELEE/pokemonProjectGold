<?php

namespace App\Http\Requests;

use App\Models\Pokemon;
use App\Models\Race;
use App\Rules\SkillJudgment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StorePokemonRequest extends FormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'The name of the pokemon.',
                'example' => 'Pikachu',
                'required' => true,  // 因為在rules中是可選的
                'type' => 'string'
            ],
            'race_id' => [
                'description' => 'The ID of the race for the pokemon.',
                'example' => 1,
                'required' => true,
                'type' => 'integer'
            ],
            'ability_id' => [
                'description' => 'The ID of the ability for the pokemon.',
                'example' => 1,
                'required' => true,
                'type' => 'integer'
            ],
            'nature_id' => [
                'description' => 'The ID of the nature for the pokemon.',
                'example' => 1,
                'required' => true,
                'type' => 'integer'
            ],
            'level' => [
                'description' => 'The level for the pokemon.',
                'example' => 1,
                'required' => true,
                'type' => 'integer'
            ],
            'skills' => [
                'description' => 'The ID of the skills for the pokemon.',
                'example' => 1,
                'required' => true,
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
        // $race_id = $this->input('race_id');
        // $race = Race::find($race_id);
        // // $miniEvolutionLevel = optional($race)->evolution_level ?? 100;  // 使用 optional 函數


        // // 找到該種族後去查找最小進化等級
        // $miniEvolutionLevel = $race->evolution_level;
        // if (!$miniEvolutionLevel){
        //     $miniEvolutionLevel = 100;
        // }

        return [
            'name' => 'required|string|max:15|unique:pokemons,name',
            'race_id' => 'required|integer|exists:races,id',
            'ability_id' => 'required|integer|exists:abilities,id',
            'nature_id' => 'required|integer|exists:natures,id',
            'level' => 'required|integer|min:1|max:100', //. $miniEvolutionLevel,
            'skills' => 'required|array|min:1|max:4',
            'skills.*' => 'integer|exists:skills,id'

        ];
    }

    public function messages()
    {
        // dd('fuck');
        return [
            'name.alpha_unicode' => '名稱只能包含中文和英文字符。',
            // ... 其他自訂的錯誤訊息 ...
        ];
    }

    // TODO validator rule
    // 或是去race取資料這個動作, 如果裡面也要做一次的話要用注入的
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // 如果已有錯誤，則直接返回不做後續驗證
            if ($validator->failed() || is_null($this->skills) || !is_array($this->skills)) {
                return true;  // 如果沒有提供技能，則直接返回，不進行後續操作
            }
            // 在這裡做了一個skills的額外驗證,確認輸入的skill是否是該種族可以學的
            $raceId = $this->input('race_id'); // 假设 race_id 是在请求中的一个字段
            $race = Race::find($raceId);

            if (!validSkillsForRace($this->skills, $race)) {
                $validator->errors()->add('skills', 'The skill is not allowed for this race.');
            }
        });
    }

    
}
