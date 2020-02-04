<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartZipCodeRequest;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;

class CartShippingController extends Controller
{
    public function update(Cart $cart, CartZipCodeRequest $request)
    {
        $cart->updateShipping($request->validated());

        return $this->success(new CartResource($cart->load('cartItems.product')));
    }
}
