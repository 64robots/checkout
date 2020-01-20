<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\CheckoutFields;
use R64\Checkout\Contracts\Shipping;
use R64\Checkout\Contracts\State;

class CheckoutSettingsController extends Controller
{
    public function index(Shipping $shipping, State $state)
    {
        $shippingMethods = collect($shipping->getShippingMethods())->map(function ($shippingMethod) {
            $shippingMethod['price'] = displayMoney($shippingMethod['price']);
            $shippingMethod['delivery_date'] = $shippingMethod['delivery_date']->format('l M d');
            return $shippingMethod;
        });

        return $this->success([
            'required' => CheckoutFields::required(),
            'states' => $state->all(),
            'shipping_methods' => $shippingMethods,
            'toc_url' => config('checkout.toc_url')
        ]);
    }
}
