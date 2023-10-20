<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    
    public function show(){
  
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userData = User::select('name','photo','email')->where('id', $user->id)->first();

        return response()->json($userData);

    }

    public function update(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    // 驗證上傳的檔案以及其他輸入字段
    $validatedData = $request->validate([
        'userPhoto' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // "sometimes" 代表此欄位為可選
        'name' => 'sometimes|required|max:255', // "sometimes" 代表此欄位為可選
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id // "sometimes" 代表此欄位為可選
    ]);

    // 檢查是否上傳了圖片
    if ($request->hasFile('userPhoto')) {
        // 刪除舊圖片
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }
    
        // 獲取檔案的副檔名
        $extension = $request->userPhoto->extension();
    
        // 創建一個新的檔案名稱，這裡是使用當前時間戳加上副檔名
        $imageName = time() . '.' . $extension;
    
        // 將檔案移動到 public/images 目錄下
        $request->userPhoto->move(public_path('images'), $imageName);
    
        // 更新使用者的照片欄位
        $user->photo = 'images/' . $imageName;
        $user->save();  // 保存更新的圖片路徑
    }
    

    // dd('fuck');
    // 更新其他已驗證的請求數據
    
    // unset($validatedData['userPhoto']);  // 去掉userPhoto，因為我們已經手動處理了
    $user->update($validatedData);

    return response()->json(['message' => 'Profile updated successfully!', 'image_path' => $user->photo ?? null], 200);
}

    // public function show(Pokemon $pokemon)
    // {
    //     $this->authorize('show', $pokemon);
    //     $pokemon->load(['user', 'ability', 'nature', 'race']);
    //     return PokemonResource::make($pokemon);
    // }

    // 超級使用者更改權限功能
    public function changeUserStatus(User $user)
    {
        // 在policy的部分定義了只有當前是超級使用者才可以操作
        $this->authorize('changePermission', $user);

        // 當狀態為0的時候改成1當狀態為1的時候改成0
        $status = $user->status;
        // if ($status == 0) { 
            // $user->update(['status' => 1]);
            // return response()->json(['message' => 'User role updated successfully']);
        // }

        $user->update(['status' => !$status]);
        return response()->json(['message' => 'User role updated successfully']);
    }



    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            // 'new_password_confirmation' => 'required|min:6'
        ]);

        $user = Auth::user();

        // 確認輸入的密碼是否與資料庫裡的相同
        $checkedPassword = Hash::check($request->current_password, $user->password);
        if (!$checkedPassword) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}
