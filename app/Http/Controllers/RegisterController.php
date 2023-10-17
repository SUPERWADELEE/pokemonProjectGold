<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // dd('fuck');
        // 1. 驗證輸入
        // 每個email在users表單都是唯一的
        // 密碼需要做確認
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 將使用者資料輸入資料庫, 目前role的部分預設為user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'=>'user'
        ]);

        event(new Registered($user));

        return response(['message' => 'User registered successfully!', 'user' => $user], 201);
    }  
    }

