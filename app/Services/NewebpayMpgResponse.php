<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use openssl_cipher_iv_length;
use openssl_decrypt;
use Illuminate\Support\Facades\Log;

class NewebpayMpgResponse
{
    protected $key;
    protected $iv;
    public $status;
    public $message;
    public $result;
    public $order_no;
    public $trans_no;

    public function __construct($params)
    {
        $this->key = config('payment.key');
        $this->iv = config('payment.iv');
        // 1. 檢查TradeInfo是否存在於$params
        if (!isset($params)) {
            Log::error('TradeInfo not found in params:', $params);
            return;
        }
        
        // 2. 解密TradeInfo
        $decryptedString = $this->decrypt($params);

        // 3. 將解密的字符串從JSON轉為陣列
        $tradeData = json_decode($decryptedString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // 如果JSON解碼有錯誤，記錄該錯誤
            Log::error('JSON decode error:', [json_last_error_msg()]);
            return;
        }

        $this->status = $tradeData['Status'] ?? null;
      
        return $tradeData;
    }


    public function isSuccess()
    {
        return $this->status === 'SUCCESS';
    }

    private function decrypt($encrypted_data)
    {
        return $this->stripPadding(openssl_decrypt(hex2bin($encrypted_data), "AES-256-CBC", $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv));
    }

    function stripPadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
}
