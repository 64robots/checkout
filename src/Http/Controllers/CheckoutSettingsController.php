<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\CheckoutFields;
use R64\Checkout\Contracts\Shipping;
use R64\Checkout\Contracts\State;

class CheckoutSettingsController extends Controller
{
    public function index(State $state)
    {
        return $this->success([
            'required' => CheckoutFields::required(),
            'states' => $state->all(),
            'toc_url' => config('checkout.toc_url')
        ]);
    }
}
