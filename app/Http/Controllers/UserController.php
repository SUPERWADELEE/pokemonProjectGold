<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 超級使用者更改權限功能
    public function changeUserStatus(User $user)
    {
        // 在policy的部分定義了只有當前是超級使用者才可以操作
        $this->authorize('changePermission', $user);

        // 當狀態為0的時候改成1當狀態為1的時候改成0
        $status = $user->status;
        if ($status == 0){
            $user->update(['status' => 1]);
            return response()->json(['message' => 'User role updated successfully']);
        }

        $user->update(['status' => 0]);
        return response()->json(['message' => 'User role updated successfully']);
    }

    public function changePassword(Request $request)
{

    // dd($request->all());

    // dd($request->input('current_password'));
    
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
        'new_password_confirmation' => 'required|min:6'
    ]);

    $user = Auth::user();

    // dd($user);
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['error' => 'Current password is incorrect'], 400);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password changed successfully']);
}

}
