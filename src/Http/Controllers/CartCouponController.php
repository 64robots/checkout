<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartCouponRequest;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;

class CartCouponController extends Controller
{
    /***************************************************************************************
     ** PUT
     ***************************************************************************************/
    public function update(Cart $cart, CartCouponRequest $request)
    {
        $cart->addCoupon($request->validated());

        return $this->success(new CartResource($cart->load(['cartItems.product', 'order'])));
    }

    public function delete(Cart $cart)
    {
        $cart->removeCoupon();

        return $this->success(new CartResource($cart->load(['cartItems.product', 'order'])));
    }
}
