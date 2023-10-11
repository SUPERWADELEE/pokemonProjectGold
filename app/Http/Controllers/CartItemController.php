<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Race;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // dd($user);
        $carts = $user->cartItems()->with(['race'])->get();
        return CartItemResource::collection($carts);
    }

    public function store(Request $request)
    {
        $race = Race::find($request->race_id);

        if (!$race) {
            return response(['error' => 'Race not found'], 404);
        }

        $raceStock = $race->stock;
        $racePrice = $race->price;

        $validationData = $request->validate(
            [
                'quantity' => 'required|int|min:1|max:' . $raceStock,
                'race_id' => 'required|int|exists:races,id'
            ],
        );

        $userId = auth()->user()->id;

        // 檢查該用戶的購物車中是否已有該商品
        $cartItem = CartItem::where('user_id', $userId)->where('race_id', $request->race_id)->first();

        // 如果已有，更新數量
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validationData['quantity'];
            // 如果發現加總過後,數量大於庫存則回傳錯誤
            if ($newQuantity > $raceStock) {
                return response(['error' => 'Requested quantity exceeds available stock'], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // 如果沒有，則創建新條目
            CartItem::create([
                'user_id' => $userId,
                'quantity' => $validationData['quantity'],
                'current_price' => $racePrice,
                'race_id' => $request->race_id
            ]);
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {

        $race = Race::find($cartItem->race_id);
        $raceStock = $race->stock;
        $validationData = $request->validate(
            [
                'quantity' => 'required|int|min:1|max:' . $raceStock,
            ],
        );
        $cartItem->update([
            'quantity' => $validationData['quantity']
        ]);
        // 計算當前購物車總金額
        $userId = auth()->user()->id;

        // 查詢該用戶的所有購物車項目的總價格
        $totalPrice = CartItem::where('user_id', $userId)
                   ->selectRaw('SUM(current_price * quantity) as total')
                   ->value('total');

        return response(['total_price' => $totalPrice], 200);
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return response()->noContent();
    }


    public function calculateTotalPrice(Request $request) {
        $request->validate([
            'cart_item_ids' => 'required|array|exists:cart_items,id',
        ]);
    
        // 確保這些項目都屬於當前用戶
        $userId = auth()->user()->id;
        $cartItems = CartItem::whereIn('id', $request->cart_item_ids)
                             ->where('user_id', $userId)
                             ->get();
    
        // 當你輸入不是這個使用者的購物車的時候
        // 數量會對不起來
        if(count($cartItems) != count($request->cart_item_ids)) {
            return response(['error' => 'Some cart items do not belong to the user or do not exist'], 403);
        }
    
        // 計算總價格
        $totalPrice = $cartItems->sum(function($item) {
            return $item->current_price * $item->quantity;
        });
    
        return response(['data' => ['total_price' => $totalPrice]], 200);
    }
    

}
