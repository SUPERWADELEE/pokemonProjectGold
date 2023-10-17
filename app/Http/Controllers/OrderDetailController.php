<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDetailRequest;
use App\Http\Resources\OrderDetailResource;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Race;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function store(OrderDetailRequest $request)
{
    $validatedData = $request->validated();

    $race = Race::find($validatedData['race_id']);
    $unit_price = $race->price;
    $user = auth()->user();

    // 从关联的购物车项中获取特定的race_id的quantity
    $cartItem = $user->cartItems()->where('race_id', $validatedData['race_id'])->first();
    $quantity = $cartItem ? $cartItem->quantity : 0;  // 如果找不到购物车项，我们默认quantity为0

    OrderDetail::create([
        'order_id' => $validatedData['order_id'],
        'race_id' => $validatedData['race_id'],
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'subtotal_price' => $quantity * $unit_price
    ]);
    
    // 扣减库存
    if ($race && $quantity) {
        $race->stock -= $quantity;
        $race->save();
    }

    // 清空当前用户的特定race_id的购物车项
    $user->cartItems()->where('race_id', $validatedData['race_id'])->delete();
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
