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
 * 
 * @authenticated
 */
class OrderController extends Controller
{
    /**
     * 訂單新增
     * 
     * @bodyParam total_price float required 訂單的總價格。
     * @bodyParam payment_status string required 訂單的付款狀態。
     * @bodyParam payment_method string required 訂單的付款方式。
     * 
     * @response {
     *     "id": "新創建的訂單的ID"
     * }
     * 
     * @param \App\Http\Requests\OrderRequest $request 使用者輸入的請求數據。
     * 
     * @return \Illuminate\Http\Response 返回新創建的訂單ID的JSON響應。
     */
    public function store(OrderRequst $request)
    {
        $validatedData = $request->validated();
        $userId = auth()->user()->id;
        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $validatedData['total_price'],
            'payment_status' => $validatedData['payment_status'],   // 自动应用 setPaymentStatusAttribute
            'payment_method' => $validatedData['payment_method'],   // 自动应用 setPaymentMethodAttribute
            'status' => 'Pending'
        ]);
        return response()->json(['id' => $order->id]);
    }

    /**
     * 訂單列表
     * 
      * @response {
     *     "data": [
     *         {
     *             ...各個訂單的資料
     *         }
     *     ]
     * }
     * 
     * @return \Illuminate\Http\Resources\Json\ResourceCollection 返回當前使用者的所有訂單的集合。
     */
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders;
        return OrderResource::collection($orders);
    }
}
