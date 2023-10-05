<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use App\Services\NewebpayMpgResponse;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function notifyResponse(Request $request)
    {
        $tradeData = new NewebpayMpgResponse($request->input('TradeInfo'));
        
         if ($tradeData){
            $createData = new PokemonController();
            $createData->add();
         }
        
    }

    
}
