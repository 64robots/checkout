<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Contracts\Shipping;

class CheckoutSettingsController extends Controller
{
    public function index(Shipping $shipping)
    {
        $shippingMethods = collect($shipping->getShippingMethods())->map(function ($shippingMethod) {
            $shippingMethod['price'] = displayMoney($shippingMethod['price']);
            return $shippingMethod;
        });

        return $this->success([
            'required' => config('checkout.required'),
            'shipping_methods' => $shippingMethods,
            'toc_url' => config('checkout.toc_url')
        ]);
    }
}
