<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 超級使用者更改權限功能
    public function changeUserStatus(User $user)
    {
        $this->authorize('changePermission', $user);

        $status = $user->status;
        
        if ($status == 0){
            $user->update(['status' => 1]);
            return response()->json(['message' => 'User role updated successfully']);
        }


        $user->update(['status' => 0]);

        return response()->json(['message' => 'User role updated successfully']);
    }
}
