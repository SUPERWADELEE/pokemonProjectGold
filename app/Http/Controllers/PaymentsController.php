<?php

namespace App\Http\Controllers;

use App\Mail\TransactionSuccessMail;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use App\Services\NewebpayMpgResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller
{
    public function notifyResponse(Request $request)
    {
        Log::info('Payment Callback Received:', ['TradeInfo' => $request->input()]);


        Log::info('Payment Callback Received:', ['TradeInfo' => $request->input('TradeInfo')]);

        // 解碼
        $tradeData = new NewebpayMpgResponse($request->input('TradeInfo'));
        $key = config('payment.key');
        $iv = config('payment.iv');

        $tradeSha = $request->input('TradeSha');
        

        $hashs = "HashKey=" . $key . "&" .$request->input('TradeInfo') . "&HashIV=" . $iv;
        $hash = strtoupper(hash("sha256", $hashs ));

        Log::info('Payment Callback Received:', ['result8' => $hash]);

        if($hash == $tradeSha){
            Log::info('Payment Callback Received:', ['result7' => 'success']);
        }
        // if($tradeData->checkSha($tradeSha)){
        //     Log::info('result5', ['MerchantID' => 'success']);
        // }

        // 確認交易是否成功
        if (!$tradeData->isSuccess()) {
             // 交易失败
            Log::info('Payment Callback Received:', ['TradeInfo' => 'fuckme']);
        } 
        // 交易成功後續動作

        $userData = [
            'name' => 'wade', // 請用真正的使用者名稱替代
            // 'transactionDetails' => $tradeData->result, // 假設您想將交易細節傳給視圖
        ];

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
    }
}
