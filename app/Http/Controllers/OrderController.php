<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequst;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

/**
 * @group Order
 * Operations related to orders.
 */
class OrderController extends Controller
{
    public function store(OrderRequst $request){
        $validatedData = $request->validated();
        $userId = auth()->user()->id;
        $order = Order::create([
        'user_id' => $userId,
        'total_price'=> $validatedData['total_price'],
        'payment_status' => $validatedData['payment_status'],   // 自动应用 setPaymentStatusAttribute
        'payment_method' => $validatedData['payment_method'],   // 自动应用 setPaymentMethodAttribute
        'status' => 'Pending'
        ]);
        return response()->json(['id' => $order->id]);
    }

    public function index(){
        $user = auth()->user();
        $orders = $user->orders;
        return OrderResource::collection($orders);
    }


 

}
