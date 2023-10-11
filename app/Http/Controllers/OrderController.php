<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequst;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(OrderRequst $request){
        $validatedData = $request->validated();
        $userId = auth()->user()->id;
        Order::create([
        'user_id' => $userId,
        'total_price'=> $validatedData['total_price'],
        'payment_status' => $validatedData['payment_status'],   // 自动应用 setPaymentStatusAttribute
        'payment_method' => $validatedData['payment_method'],   // 自动应用 setPaymentMethodAttribute
        'status' => 'Pending'
        ]);
    }

    public function show(){
        $user = auth()->user();
        $orders = $user->orders;
        return OrderResource::collection($orders);
    }



}
