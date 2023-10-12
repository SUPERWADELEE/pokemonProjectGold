<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDetailRequest;
use App\Http\Resources\OrderDetailResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Race;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function store(OrderDetailRequest $request)
    {
        $validatedData = $request->validated();

        $unit_price = Race::find($validatedData['race_id'])->price;

        $quantity = $validatedData['quantity'];

        OrderDetail::create([
            'order_id' => $validatedData['order_id'],
            'race_id' => $validatedData['race_id'],
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'subtotal_price' => $quantity * $unit_price
        ]);
    }

    public function index(Order $order)
    {
        // 直接從訂單中獲取所有的訂單詳情
        $orderDetails = $order->orderDetails()->with('race')->get();

        return OrderDetailResource::collection($orderDetails);
    }

    public function show(OrderDetail $orderDetail)
{
    $user = auth()->user();
    // 验证此 OrderDetail 是否属于当前用户
    if ($orderDetail->order->user_id != $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return new OrderDetailResource($orderDetail);
}

}
