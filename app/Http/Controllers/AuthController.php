<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
            // 先針對輸入的部分做驗表單驗證       
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            // 根據進來的guard設定,去預先的表單設定查看輸入的資料是否存在
            $token = JWTAuth::attempt($credentials);
    
            if (!$token) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
    
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => Auth::user()
            ], 200);
    }
    


    public function logout()
    {
        try {
            // 取出token 將其失效
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout'], 500);
        }

        
    }
    
}
