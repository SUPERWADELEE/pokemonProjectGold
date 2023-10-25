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
    public $MerchantID;
    public $Amt;
    public $MerchantOrderNo;
    public $TradeNo;

   

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
        // Log::error('result1', ['MerchantID' => $tradeData["Result"]["MerchantID"]]);
        $this->MerchantID =$tradeData["Result"]["MerchantID"]; 

        $this->Amt=$tradeData["Result"]["Amt"];

        $this->MerchantOrderNo=$tradeData["Result"]["MerchantOrderNo"];

        $this->TradeNo=$tradeData["Result"]["TradeNo"];
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

    function checkSha($tradeSha){
        $check_code = array(
            "MerchantID" =>$this->MerchantID,
            "Amt" => $this->Amt,
            "MerchantOrderNo" => $this->MerchantOrderNo,
            "TradeNo" => $this->TradeNo
        );
        ksort($check_code);
        $check_str = http_build_query($check_code);
        $CheckCode = "HashIV=".$this->iv."&$check_str&HashKey=".$this->key."";
        $CheckCode = strtoupper(hash("sha256", $CheckCode));

        Log::error('result5', ['MerchantID' => $CheckCode]);
         

        if ($tradeSha ==$CheckCode){
            return true;
        }
    }
}
