<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Illuminate\Http\Request;

class NatureController extends Controller
{
    public function index()
    {
        $allNatures = Nature::select('id', 'name')->get();
        return $allNatures;
    }


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
