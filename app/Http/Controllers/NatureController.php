<?php

namespace App\Http\Controllers;

use App\Models\Nature;
use Illuminate\Http\Request;

/**
 * @group Nature
 * Operations related to natures.
 * 
 * @authenticated
 */
class NatureController extends Controller
{
    /**
     * 性格詳情
     * 
     * 主要在於可以讓使用更新寶可夢的時候，在性格部分有選項可以讓使用者選擇
     * @response 200 [{
     *     "id": "性格的 ID",
     *     "name": "性格的名稱"
     * }]
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": ["名稱只能包含中文和英文字。"]
     *   }
     * }
     * @return \Illuminate\Http\Response 所有性格的列表，每個性格包括其ID和名稱。
     */
    public function index()
    {
        $allNatures = Nature::select('id', 'name')->get();
        return $allNatures;
    }


    /**
     * 性格新增
     * 
     * 
     * @bodyParam name string required 性格的名稱。要求是唯一的且最多包含255個字符且只能輸入英文或中文字。Example:好害羞
     * 
     * @response 201 {
     *     "message": "Nature saved successfully"
     * }
     * 
     * @response 400 {
     *     "error": "Validation error messages if any"
     * }
     * 
     * @param \Illuminate\Http\Request $request 使用者輸入的請求數據。
     * 
     * @return \Illuminate\Http\Response 用 JSON 格式返回的成功消息或驗證錯誤。
     */

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

    /**
     * 性格修改
     * 
      * @bodyParam name string required 性格的新名稱。要求是唯一的且最多包含255個字符。Example:易怒
     * 
     * @response 200 {
     *     "message": "Nature updated successfully"
     * }
     * 
     * @response 400 {
     *     "error": "Validation error messages if any"
     * }
     * 
     * @response 404 {
     *     "message": "Nature not found"
     * }
     * 
     * @param \Illuminate\Http\Request $request 使用者輸入的請求數據。
     * @param \App\Nature $nature 從資料庫取得的指定性格模型實例。
     * 
     * @return \Illuminate\Http\Response 用 JSON 格式返回的成功消息或錯誤消息。
     */
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
