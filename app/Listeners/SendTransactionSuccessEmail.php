<?php

namespace App\Listeners;

use App\Events\TransactionSuccess;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionSuccessMail;
use Illuminate\Support\Facades\Log;

class SendTransactionSuccessEmail
{
    public function handle(TransactionSuccess $event)
    {
        $email = $event->email;
        $userData = $event->userData;

        Mail::to($email)
            ->send(new TransactionSuccessMail($userData));
    }
}

