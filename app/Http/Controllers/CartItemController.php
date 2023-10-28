<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Race;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group CartItem
 * Operations related to cartItems.
 * 
 * @authenticated
 */

class CartItemController extends Controller
{
    /**
     * 顯示購物車商品
     * 
     * 這部分主要是用來顯示購物車頁面的商品資訊
     * 
     * @response 200 {
     *     "data": [
     *         {
     *             "id": "項目ID",
     *             "amount": "購物車中的商品數量",
     *             "current_price": "當前種族價格（如果種族已加載）",
     *             "race_name": "種族名稱（如果種族已加載）",
     *             "race_photo": "種族圖片URL（如果種族已加載）",
     *             "race_id": "種族ID（如果種族已加載）"
     *         },
     *       
     *     ]
     * }
     * 
     * @response 401 {
     *     "message": "Unauthenticated."
     * }
     * 
     * @return \Illuminate\Http\Response
     * 
     */

    public function index()
    {
        $user = auth()->user();

        $carts = $user->cartItems()->with(['race'])->get();
        return CartItemResource::collection($carts);
    }

    /**
     * 加入購物車
     * 
       * @param \Illuminate\Http\Request $request
     * 
     * @bodyParam quantity int required 購買的數量，必須在1到庫存的範圍內。Example: 2
     * @bodyParam race_id int required 種族的ID，必須存在於種族表中。Example: 5
     * 
     * @response 200 {
     *     "message": "Item added to cart successfully."
     * }
     * 
     * @response 400 {
     *     "error": "Requested quantity exceeds available stock."
     * }
     * 
     * @response 404 {
     *     "error": "Race not found."
     * }
     * 
     * @return \Illuminate\Http\Response
     * 
     */

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
                'race_id' => $request->race_id,


            ]);
        }
    }

    /**
     * 購物車更新
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CartItem $cartItem 購物車的項目
     * 
     * @bodyParam quantity int required 更新的商品數量，必須在1到庫存的範圍內。Example: 3
     * 
     * @response 200 {
     *     "total_price": "總金額"
     * }
     * 
     * @response 400 {
     *     "error": "Validation error message."
     * }
     * 
     * @return \Illuminate\Http\Response 包含購物車的總金額的響應
     */
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

    /**
     * 購物車刪除
     * 
      * @param \App\Models\CartItem $cartItem 購物車的項目
     * 
     * @response 204
     * 
     * @return \Illuminate\Http\Response 返回無內容的204響應，表示成功刪除
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return response()->noContent();
    }


    /**
     * 購物車總價格計算
     * 
      * @param \Illuminate\Http\Request $request
     * 
     * @bodyParam cart_item_ids array required 需要計算的購物車項目ID的列表。Example: [1, 2, 3]
     * 
     * @response 200 {
     *     "data": {
     *         "total_price": "計算的總金額"
     *     }
     * }
     * 
     * @response 403 {
     *     "error": "Some cart items do not belong to the user or do not exist"
     * }
     * 
     * @return \Illuminate\Http\Response 返回計算的總金額的響應
     */
    public function calculateTotalPrice(Request $request)
    {
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
        if (count($cartItems) != count($request->cart_item_ids)) {
            return response(['error' => 'Some cart items do not belong to the user or do not exist'], 403);
        }

        // 計算總價格
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->current_price * $item->quantity;
        });

        return response(['data' => ['total_price' => $totalPrice]], 200);
    }
}
