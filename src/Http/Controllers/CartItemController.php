<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\CartItemRequest;
use R64\Checkout\Http\Resources\CartItemResource;
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
        $cartItem = CartItem::makeOne($cart, $request->validated());

        return $this->success(new CartItemResource($cartItem));
    }

    /***************************************************************************************
     ** PUT
     ***************************************************************************************/
    public function update(CartItem $cartItem, Request $request)
    {
        $cartItem->updateMe($request->only('quantity'));

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
