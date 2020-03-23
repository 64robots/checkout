<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartEmailRequest;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;

class CartEmailController extends Controller
{
    public function update(Cart $cart, CartEmailRequest $request)
    {
        $cart->updateEmail($request->validated());

        return $this->success(new CartResource($cart->load('cartItems.product')));
    }
}
