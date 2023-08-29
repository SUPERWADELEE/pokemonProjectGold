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
        $race = Race::find($this->input('race_id'));
        $miniEvolutionLevel = $race->evolution_level;

        return [
            'name' => 'required|string|max:255',
            'race_id' => 'required|integer|exists:races,id',
            'ability_id' => 'required|integer|exists:abilities,id',
            'nature_id' => 'required|integer|exists:natures,id',
            'level' => 'required|integer|max:' . $miniEvolutionLevel,
            'skills' => ['required','array','min:1','max:4', new SkillJudgment()]
            
        ];
    }

    // public function valid_skills_for_race($skills) {
    //     $pokemonId = request()->input('race_id');
    //     $pokemon = Race::find($pokemonId);
    //     $allowedSkills = $pokemon->skills->pluck('id')->toArray();
        
    //     foreach ($skills as $skillId) {
    //         if (!in_array($skillId, $allowedSkills)) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }

   
}
