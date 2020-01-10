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
        $shippingMethod = $shipping->find($request->get('shipping_id'));
        $shippingPrice = Arr::get($shippingMethod, 'price', 0);

        return $this->success([
            'total' => displayMoney($cart->total + $shippingPrice)
        ]);
    }
}
