<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function store(){
        $userId = auth()->user()->id;
        ShoppingCart::create([
            'user_id' => $userId
        ]);

        return response(['message' => 'Shopping cart created successfully!'], 201);

    }
}


