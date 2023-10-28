<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group Payment
 * Operations related to payments.
 * 
 * @authenticated
 */
class PaymentController extends Controller
{
    /**
     * 請求藍星金流結帳頁面
     * 
     * 此方法主要功能如下：
     * 1. 验证当前用户。
     * 2. 更新与当前用户关联的购物车项目的结账状态。
     * 3. 生成与藍星金流相关的支付参数（包括加密和哈希）。
     * 4. 返回支付参数，以便前端将用户重定向到藍星金流的支付页面。
     * 
     * @apiGroup 支付
     * 
     * @bodyParam totalPrice float required 购物车中所有商品的总价格。
     * 
     * @param \Illuminate\Http\Request $request 用戶的HTTP請求。
     * 
     * @return \Illuminate\Http\JsonResponse 返回包含支付参数的JSON响应。
     */
    public function checkout(Request $request)
    {
        // 获取当前经过身份验证的用户
        $user = JWTAuth::parseToken()->authenticate();

        // 获取用户的ID
        $userId = $user->id;

        // 更新与该用户关联的购物车项目的结账状态
        CartItem::where('user_id', $userId)->update(['checkout_status' => 'checked']);

        $totalPrice = $request->input('totalPrice');

        $key = config('payment.key');
        $iv = config('payment.iv');
        $mid = config('payment.id');
        $notifyURL = config('payment.notify_url');
        $returnURL = config('payment.return_url');
        $payment = config('payment.payment_url');

        $tradeInfo = http_build_query(array(
            'MerchantID' => $mid,
            'RespondType' => 'JSON',
            'TimeStamp' => time(),
            'Version' => '2.0',
            'MerchantOrderNo' => "test0315001" . time(),
            'Amt' => $totalPrice,
            'ItemDesc' => 'test',
            'NotifyURL' => $notifyURL,
            'ReturnURL' => $returnURL,
        ));

        $encodedData = bin2hex(openssl_encrypt(
            $tradeInfo,
            "AES-256-CBC",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        ));


        $hashs = "HashKey=" . $key . "&" . $encodedData . "&HashIV=" . $iv;
        $hash = strtoupper(hash("sha256", $hashs));



        return response()->json([
            'payment_url' => $payment,
            'mid' => $mid,
            'edata1' => $encodedData,
            'hash' => $hash
        ]);
    }
}
