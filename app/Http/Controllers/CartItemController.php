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
    public function index(){
        $user = auth()->user();
        // dd($user);
        $carts = $user->cartItems()->with(['race'])->get();
        return CartItemResource::collection($carts);

       
    }

    public function store(Request $request){
    $race = Race::find($request->race_id);

    if (!$race) {
        return response(['error' => 'Race not found'], 404);
    }

    $raceStock = $race->stock;
    $racePrice = $race->price;

    $validationData = $request->validate(
        [
            'quantity' => 'required|int|min:1|max:'.$raceStock,
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

    

    
}



