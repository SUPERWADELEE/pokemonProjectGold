<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Illuminate\Http\Request;

/**
 * @group Nature
 * Operations related to natures.
 */
class NatureController extends Controller
{
    // 性格詳情
    public function index()
    {
        // dd('fuck');
        $allNatures = Nature::select('id', 'name')->get();
        return $allNatures;
    }


    // 性格新增
    public function store(Request $request)
    {
        $validationData = $request->validate(
            [
                'name' => 'required|max:255|string|unique:natures,name',
            ],
            [
                'name.alpha_unicode' => '名稱只能包含中文和英文字符。',
            ]
        );

        Nature::create([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Nature saved successfully'], 201);
    }


    // 性格修改
    public function update(Request $request, Nature $nature)
    {
        $validationData = $request->validate(
            [
                'name' => 'required|max:255|alpha_unicode|unique:natures,name',
            ],
            [
                'name.alpha_unicode' => '名稱只能包含中文和英文字符。',
            ]
        );



        if (!$nature) {
            return response(['message' => 'Nature not found'], 404);
        }

        $nature->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Nature updated successfully'], 200);
    }
}
