<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group GoogleLogin
 * Operations related to googleLogin.
 */

class GoogleLoginController extends Controller
{


    /**
     * 將使用者導向google第三方服務頁面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse Google 第三方認證頁面的重定向響應。
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }


     /**
     * 處理從 Google 第三方認證服務頁面返回的回調。
     *
     * 
     * 
     * @response 200 {
     *     "message": "Login successful via Google",
     *     "token": "使用者的 JWT 令牌",
     *     "user": "使用者的資料"
     * }
     * 
     * @return \Illuminate\Http\Response 用 JSON 格式返回的成功消息、JWT 令牌和使用者資訊。
     */
    

    public function handleProviderCallback()
    {
        $socialUser = Socialite::driver('google')->user();

        // 使用 email 查找本地使用者
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                // 你也可以保存其他從 Google 取得的資訊，例如頭像、ID 等
                // 'avatar' => $socialUser->getAvatar(),
                // 'google_id' => $socialUser->getId(),

                // 你可能需要一個隨機密碼，因為某些驗證方法需要它，即使使用者從未使用它
                'password' => bcrypt(Str::random(16))
            ]
        );

        // 為用戶生成 JWT
        $token = JWTAuth::fromUser($user);

        // 返回令牌給前端
        return response()->json([
            'message' => 'Login successful via Google',
            'token' => $token,
            'user' => $user
        ], 200);
        // return redirect("/?token={$token}");
    }
}
