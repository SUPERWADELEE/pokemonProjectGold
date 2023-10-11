<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
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

    
}



