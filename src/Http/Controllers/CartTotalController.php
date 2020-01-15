<?php

namespace R64\Checkout\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use R64\Checkout\Contracts\Shipping;
use R64\Checkout\Models\Cart;

class CartTotalController extends Controller
{
    /***************************************************************************************
     ** GET
     ***************************************************************************************/
    public function get(Cart $cart, Request $request, Shipping $shipping)
    {
        $total = $cart->calculateTotal($request->shipping_id);

        return $this->success([
            'total' => displayMoney($total)
        ]);
    }
}
