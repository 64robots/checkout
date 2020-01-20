<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\OrderEstimateHandler as OrderEstimateHandlerContract;
use R64\Checkout\Models\Cart;

class OrderEstimateHandler implements OrderEstimateHandlerContract
{
    public function calculate(Cart $cart, array $shippingAddress)
    {
        return [
            'subtotal' => $cart->ite,
            'tax' => config('checkout.tax'),
            'discount' => 0,
            'shipping' => 0,
            'total' => 0
        ];
    }
}
