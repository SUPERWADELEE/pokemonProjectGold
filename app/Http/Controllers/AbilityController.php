<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use Illuminate\Http\Request;

/**
 * @group Ability
 * Operations related to abilities.
 * 
 * @authenticated
 */
class AbilityController extends Controller
{

    /**
     * 特性列表
     * 
     * 主要在於可以讓使用更新寶可夢的時候，在特性部分有選項可以讓使用者選擇
     * 
     * @response {
     *  "id": "3",
     *  "name": "strong",
     * }
     */
    public function index()
    {
        $allNatures = Ability::select('id', 'name')->get();
        return $allNatures;
    }


    /**
     * 特性新增
     * 
     *@bodyParam name string required 能力的名稱. 必須是唯一的，只能包含中文和英文字，且最大長度為255個字符. Example: superHard

     * @response 201 {
     *   "message": "Ability saved successfully"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": ["名稱只能包含中文和英文字。"]
     *   }
     * }
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

        Ability::create([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability saved successfully'], 201);
    }


    /**
     * 特性修改
     * 
     * 
     * 此端點允許您更新現有的特性。
     *
     * @param Ability $ability 特性的模型實例
     * 
     * @bodyParam name string required 需要更新的特性名稱. 必須是唯一的，只能包含中文和英文字符，且最大長度為255個字符.Example: superSoft
     *
     * @response 200 {
     *   "message": "Ability updated successfully"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": ["名稱只能包含中文和英文字符。"]
     *   }
     * }
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

        $ability->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability updated successfully'], 200);
    }
}
