<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Contracts\Shipping;

class CheckoutSettingsController extends Controller
{
    public function index(Shipping $shipping)
    {
        return $this->success([
            'required' => config('checkout.required'),
            'shipping_methods' => $shipping->getShippingMethods()
        ]);
    }
}
