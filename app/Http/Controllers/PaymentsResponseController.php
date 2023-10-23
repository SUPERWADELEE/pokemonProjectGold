<?php

namespace App\Http\Controllers;

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

        if ($this->isHashMatched($tradeInfo, $tradeSha)) {
            Log::info('Payment Callback Received:', ['Result' => 'Hash Matched']);
        }

        if (!$tradeData->isSuccess()) {
            Log::info('Payment Callback Received:', ['Result' => 'Failure']);
        }

        
        $emails = $this->getEmailsOfCheckedOutUsers();
        // Log::info('Payment Callback Received:', ['email' => $emails]); 
        $this->sendEmail($emails);
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
    
        return $email;
    }
    

    public function sendEmail($emails)
    {
        // 交易成功後續動作
        Log::info('Payment Callback Received:', ['Result' => $emails]);

        $userData = [
            'name' => 'wade', // 請用真正的使用者名稱替代
            // 'transactionDetails' => $tradeData->result, // 假設您想將交易細節傳給視圖
        ];

        Mail::to($emails) // 使用者的電子郵件地址
            ->send(new TransactionSuccessMail($userData));
    }
}



     // 交易成功後續動作

//      $userData = [
//         'name' => 'wade', // 請用真正的使用者名稱替代
//         // 'transactionDetails' => $tradeData->result, // 假設您想將交易細節傳給視圖
//     // ]
// }

       

        // Log::info('Payment Callback Received:', ['TradeInfo' => $tradeData]);
        // Mail::to('elvis122545735@gmail.com') // 使用者的電子郵件地址
        // ->send(new TransactionSuccessMail($userData));
          


        // $tradeData->isSuccess();

        // $data = json_decode($request->all(), true); // 設定第二個參數為 true，使其返回關聯陣列
        // $tradeInfo = $data['TradeInfo'];
        // $key = config('payment.key');
        // $iv = config('payment.iv');
        // $mid = config('payment.id');
        // $notifyURL = config('payment.notify_url');
        // $returnURL = config('payment.return_url');
        // $payment = config('payment.payment_url');
        // $data1 = "8a0127446da7f8f4..."; // 這裡換成你從藍星接收到的 TradeInfo 值

        // function strippadding($string)
        // {
        //     $slast = ord(substr($string, -1));
        //     $slastc = chr($slast);
        //     $pcheck = substr($string, -$slast);
        //     if (preg_match("/$slastc{" . $slast . "}/", $string)) {
        //         $string = substr($string, 0, strlen($string) - $slast);
        //         return $string;
        //     } else {
        //         return false;
        //     }
        // }

        // $edata1 = strippadding(openssl_decrypt(hex2bin($data1), "AES-256-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv));
        // echo "解密後資料= " . $edata1 . "\n";




        // Log::info('Payment Callback Received:', $request->all());
