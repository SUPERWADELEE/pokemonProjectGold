<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    // 性格詳情
    public function index()
    {
        $allNatures = Ability::all();
        return $allNatures;
    }


    // 性格新增
    public function store(Request $request)
    {
        $validationData = $request->validate([
            'name' => 'required|max:255',
        ]);

        Ability::create([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability saved successfully'], 201);
    }


    // 性格修改
    public function update(Request $request, Ability $ability)
    {
        $validationData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $ability->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Ability updated successfully'], 200);
    }
}
