<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Facades\Customer;
use R64\Checkout\Facades\Product;
use R64\Checkout\Http\Requests\CartRequest;
use R64\Checkout\Http\Resources\CartResource;
use R64\Checkout\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /***************************************************************************************
     ** GET
     ***************************************************************************************/
    public function get(Cart $cart)
    {
        return $this->success(new CartResource($cart->load(['cartItems.product', 'order'])));
    }

    /***************************************************************************************
     ** POST
     ***************************************************************************************/
    public function create(CartRequest $request)
    {
        $customerForeignKey = Customer::getForeignKey();
        $productForeignKey = Product::getForeignKey();

        $cart = Cart::makeOne([
            $customerForeignKey => auth()->user()->id ?? null,
            'ip_address' => $request->ip(),
            $productForeignKey => $request->get($productForeignKey)
        ]);

        return $this->success(new CartResource($cart->fresh()->load(['cartItems.product', 'order'])));
    }

    /***************************************************************************************
     ** PUT
     ***************************************************************************************/
    public function update(Cart $cart, CartRequest $request)
    {
        $cart->updateMe($request->validated());

        return $this->success(new CartResource($cart->load(['cartItems.product', 'order'])));
    }

    /***************************************************************************************
     ** DELETE
     ***************************************************************************************/
    public function delete(Cart $cart)
    {
        $cart->delete();

        return $this->success();
    }
}
