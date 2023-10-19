<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
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
        // echo "Data=[" . $data1 . "]<br><br>";
        $encodedData = bin2hex(openssl_encrypt(
            $tradeInfo,
            "AES-256-CBC",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        ));

        // log::info('Received notification:', ['all' => $request->input('TradeInfo')]);
        $hashs = "HashKey=" . $key . "&" . $encodedData . "&HashIV=" . $iv;
        $hash = strtoupper(hash("sha256", $hashs));


        // dd('fuck');
        return response()->json([
            'payment_url' => $payment,
            'mid' => $mid,
            'edata1' => $encodedData,
            'hash' => $hash
        ]);
    }
}
