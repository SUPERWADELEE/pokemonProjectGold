<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
