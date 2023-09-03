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
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            $token = JWTAuth::attempt($credentials);
    
            if (!$token) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
    
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => Auth::user()
            ], 200);
    
        } catch (Exception $e) {
            // 返回一個具有錯誤信息的JSON響應
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    


    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to logout'], 500);
        }
    }
    
}
