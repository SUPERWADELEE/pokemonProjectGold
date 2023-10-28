<?php

namespace App\Http\Controllers;

use App\Events\TransactionSuccess;
use App\Mail\TransactionSuccessMail;
use App\Models\CartItem;
use App\Models\Pokemon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NewebpayMpgResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @group PaymentResponse
 * 
 * 此為藍星金流在結帳完之後會通知後台的，接收結果用的API
 *
 * 
 * @authenticated
 */

class PaymentsResponseController extends Controller
{
    /**
     * 藍星金流結帳完後結果返回確認，寄通知信給使用者
     * 
     * 此方法主要功能如下：
     * 1. 從请求中提取支付相关的信息。
     * 2. 验证支付回调的数字签名以确保数据的完整性。
     * 3. 根据支付结果记录相关的日志信息。
     * 4. 在支付成功后向用户发送通知邮件。
     * 
     * @apiGroup 支付
     * 
     * @bodyParam TradeInfo string required 支付相关的加密数据。
     * @bodyParam TradeSha string required 支付回调的数字签名。
     * 
     * @param \Illuminate\Http\Request $request 用户的HTTP请求，包含支付相关的信息。
     * 
     * @return void
     */
    public function notifyResponse(Request $request)
    {
        $tradeInfo = $request->input('TradeInfo');
        $tradeSha = $request->input('TradeSha');
        $tradeData = new NewebpayMpgResponse($tradeInfo);

        // 比對hash(數位簽章)
        if ($this->isHashMatched($tradeInfo, $tradeSha)) {
            Log::info('Payment Callback Received:', ['Result' => 'Hash Matched']);
        }

        // 觀察結帳狀態是否成功
        if (!$tradeData->isSuccess()) {
            Log::info('Payment Callback Received:', ['Result' => 'Failure']);
        }

        // 得到結帳的人的email
        $emails = $this->getEmailsOfCheckedOutUsers();


        $userData = [
            'name' => 'wade',
        ];

        // 寄信
        event(new TransactionSuccess($emails, $userData));
    }

    private function isHashMatched($tradeInfo, $tradeSha)
    {
        $key = config('payment.key');
        $iv = config('payment.iv');
        $hashs = "HashKey=$key&$tradeInfo&HashIV=$iv";
        $hash = strtoupper(hash("sha256", $hashs));

        return $hash == $tradeSha;
    }


    public function getEmailsOfCheckedOutUsers()
    {
        $checkedOutUserId = CartItem::where('checkout_status', 'checked')->pluck('user_id')->unique()->first();
        Log::info('Payment Callback Received:', ['userId' => $checkedOutUserId]);

        // 如果没有找到用户，您可能想要记录一个错误或执行其他逻辑
        if ($checkedOutUserId === null) {
            Log::error('No checked out user found.');
            return null;
        }

        $email = User::where('id', $checkedOutUserId)->value('email');
        Log::info('Payment Callback Received:', ['email' => $email]);
        CartItem::where('user_id', $checkedOutUserId)->update(['checkout_status' => 'finished']);

        return $email;
    }
}
