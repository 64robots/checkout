<?php

namespace R64\Checkout\Http\Controllers;

class CheckoutSettingsController
{
    public function index()
    {
        return config('checkout.required');
    }
}
