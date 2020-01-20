<?php

namespace R64\Checkout\Http\Controllers;

use Illuminate\Http\Request;
use R64\Checkout\Models\Cart;
use R64\Checkout\Contracts\OrderEstimateHandler;

class OrderEstimateController extends Controller
{
    /***************************************************************************************
     ** GET
     ***************************************************************************************/
    public function get(Cart $cart, Request $request, OrderEstimateHandler $estimateHandler)
    {
        $estimate = $estimateHandler->calculate($cart, $request->shipping);

        return $this->success([
            'subtotal' => $estimate['subtotal'],
            'tax' => $estimate['tax'],
            'discount' => $estimate['discount'],
            'shipping' => $estimate['shipping'],
            'total' => $estimate['total']
        ]);
    }
}
