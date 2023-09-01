<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Illuminate\Http\Request;

class NatureController extends Controller
{
    // 性格詳情
    public function index()
    {
        // 創建一個單獨的 `Nature` 模型實例並保存到資料庫
        // $nature = Nature::factory()->make();

        // 創建三個 `Nature` 模型實例並保存到資料庫
        // $natures = Nature::factory(3)->create();

        $allNatures = Nature::all();
        return $allNatures;
    }


    // 性格新增
    public function store(Request $request)
    {
        $validationData = $request->validate([
            'name' => 'required|max:255',
        ]);

        Nature::create([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Nature saved successfully'], 201);
    }


    // 性格修改
    public function update(Request $request, $id)
    {
        $validationData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $nature = Nature::find($id);

        if (!$nature) {
            return response(['message' => 'Nature not found'], 404);
        }

        $nature->update([
            'name' => $validationData['name']
        ]);

        return response(['message' => 'Nature updated successfully'], 200);
    }
}
