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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        

        $race_id = $this->input('race_id');
        // 拿到輸入的種族id,並在race表格中查找
        $race = Race::find($race_id);

        // dd($race);
        // 找到該種族後去查找最小進化等級
        $miniEvolutionLevel = $race->evolution_level;
        if (!$miniEvolutionLevel){
            $miniEvolutionLevel = 100;
        }
        
        

        return [
            'name' => 'required|string|max:255',
            'race_id' => 'required|integer|exists:races,id',
            'ability_id' => 'required|integer|exists:abilities,id',
            'nature_id' => 'required|integer|exists:natures,id',
            'level' => 'required|integer|max:' . $miniEvolutionLevel,
            'skills' => 'required|array|min:1|max:4'
            
        ];
    }

    public function withValidator($validator)
    {
        // dd('fuck');
        $validator->after(function ($validator) {
            // 在這裡做了一個skills的額外驗證,確認輸入的skill是否是該種族可以學的
            if (!valid_skills_for_race($this->skills)) {
                
                $validator->errors()->add('skills', 'The skill is not allowed for this race.');
            }

            
        });
    }

   
}
