<?php

namespace App\Rules;

use App\Models\Race;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SkillJudgment implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
    }

    public function passes($attribute, $value)
    {
    //     dd('fuck');
    //     $pokemonId = request()->input('race_id');
    //     $pokemon = Race::find($pokemonId);
    //     $allowedSkills = $pokemon->skills->pluck('id')->toArray();
        
    //     foreach ($value as $skillId) {
    //         if (!in_array($skillId, $allowedSkills)) {
    //             return false;
    //         }
    //     }
    //     return true;
    return false;
    }

    public function message()
    {
        return 'The skill is not allowed for this race.';
    }
}
