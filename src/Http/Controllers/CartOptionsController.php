<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartOptionsRequest;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;

class CartOptionsController extends Controller
{
    public function store(Cart $cart, CartOptionsRequest $request)
    {
        $cart->addOptions($request->validated()['options']);

        return $this->success(new CartResource($cart->load('cartItems.product')));
    }
}
