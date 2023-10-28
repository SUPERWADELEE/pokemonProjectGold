<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * @group Auth
 * Operations related to auth.
 */
class AuthController extends Controller
{
    /**
     * 登入
     * 
     * 此端點允許用戶使用他們的電子郵件和密碼來登入系統，並返回一個JWT令牌。
     *
     * @param Request $request 請求物件，包含電子郵件和密碼
     * 
     * @bodyParam email string required 用戶的電子郵件地址。例子：user@example.com
     * @bodyParam password string required 用戶的密碼。
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "token": "Generated JWT token",
     *   "user": "Authenticated user object"
     * }
     * 
     * @response 401 {
     *   "error": "Invalid credentials"
     * }
     */
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



    /**
     * 登出
     * 
     * 此端點允許已經登入的用戶登出，它會使當前的JWT令牌失效。
     *
     * @response 200 {
     *   "message": "Successfully logged out"
     * }
     * 
     * @response 500 {
     *   "message": "Failed to logout"
     * }
     */
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

    /**
     * 註冊email驗證信確認
     * 
    
     * 電子郵件驗證確認
     *
     * 此端點用於確認用戶的電子郵件驗證。它會比對提供的hash值和用戶的電子郵件生成的hash值。
     * 如果驗證成功，該用戶的電子郵件將被標記為已驗證，並且將觸發一個已驗證的事件。
     *
     * @param Request $request HTTP請求
     * @param int $id 用戶ID
     * @param string $hash 從驗證郵件中提供的hash值
     * 
     * @throws AuthorizationException 當提供的hash值不匹配時
     * 
     * @response 200 {
     *   "message": "Email verified successfully."
     * }
     * 
     * @response 200 {
     *   "message": "Email already verified."
     * }
     */

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return response(['message' => 'Email already verified.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response(['message' => 'Email verified successfully.']);
    }

    /**
     * 確認user表註冊驗證信狀態
     * 
     * 此端點用於檢查指定電子郵件的用戶是否已完成電子郵件驗證。
     * 它將返回用戶的電子郵件驗證狀態，無論是已驗證還是未驗證。
     *
     * @param string $email 需要檢查的用戶電子郵件地址
     * 
     * @response 200 {
     *   "isVerified": true/false
     * }
     * 
     * @response 404 {
     *   "error": "User not found"
     * }
     */
    public function checkVerificationStatus($email)
    {
        // Find the user by email
        $user = User::where('email', $email)->first();

        // If the user doesn't exist, return an error
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user's email is verified
        $isVerified = !is_null($user->email_verified_at);

        // Return the verification status
        return response()->json(['isVerified' => $isVerified]);
    }
}
