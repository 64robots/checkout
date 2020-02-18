<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartItemRequest;
use R64\Checkout\Http\Resources\CartItemResource;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /***************************************************************************************
     ** POST
     ***************************************************************************************/
    public function create(CartItemRequest $request, Cart $cart)
    {
        CartItem::makeOne($cart, $request->validated());

        $cart = $cart->fresh();
        $cart->load('cartItems.product');

        return $this->success(new CartResource($cart));
    }

    /***************************************************************************************
     ** PUT
     ***************************************************************************************/
    public function update(CartItem $cartItem, CartItemRequest $request)
    {
        $cartItem->updateMe($request->validated());

        return $this->success(new CartItemResource($cartItem));
    }

    /***************************************************************************************
     ** DELETE
     ***************************************************************************************/
    public function delete(CartItem $cartItem)
    {
        $cartItem->delete();

        return $this->success();
    }
}
