<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use Illuminate\Http\Request;

/**
 * @group Ability
 * Operations related to abilities.
 */
class AbilityController extends Controller
{

    /**
     * 性格列表
     */
    public function index()
    {
        $allNatures = Ability::select('id', 'name')->get();
        return $allNatures;
    }


    /**
     * 性格新增
     */
    public function store(Request $request)
    {

        $validationData = $request->validate(
            [
                'name' => 'required|max:255|alpha_unicode|unique:abilities,name',
            ],
            [
                'name.alpha_unicode' => '名稱只能包含中文和英文字。',
            ]
        );

        // dd($validationData);
        Ability::create([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability saved successfully'], 201);
    }


    /**
     * 性格修改
     */
    public function update(Request $request, Ability $ability)
    {
        $validationData = $request->validate(
            [
                'name' => 'required|max:255|alpha_unicode|unique:abilities,name',
            ],
            [
                'name.alpha_unicode' => '名稱只能包含中文和英文字符。',
            ]
        );

        // if (!$ability) {
        //     return response(['message' => 'Ability not found'], 404);
        // }

        $ability->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability updated successfully'], 200);
    }
}
