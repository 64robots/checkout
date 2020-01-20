<?php

namespace R64\Checkout\Contracts;

use R64\Checkout\Models\Cart;

interface OrderEstimateHandler
{
    public function calculate(Cart $cart, array $shippingAddress);
}
