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

class PaymentsResponseController extends Controller
{
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
    

    // public function sendEmail($emails)
    // {
    //     // 交易成功後續動作
    //     Log::info('Payment Callback Received:', ['Result' => $emails]);

    //     $userData = [
    //         'name' => 'wade', // 請用真正的使用者名稱替代
    //         // 'transactionDetails' => $tradeData->result, // 假設您想將交易細節傳給視圖
    //     ];

        
    //     Mail::to($emails) // 使用者的電子郵件地址
    //         ->send(new TransactionSuccessMail($userData));
    // }
}



   
