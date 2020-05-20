<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Events\NewOrder;
use R64\Checkout\Events\NewOrderPurchase;
use R64\Checkout\Http\Requests\OrderRequest;
use R64\Checkout\Http\Resources\OrderResource;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Customer;
use R64\Checkout\Models\Order;
use R64\Checkout\Models\OrderPurchase;
use R64\Checkout\GuestCustomer;
use R64\Checkout\Contracts\PaymentHandler;

class OrderController extends Controller
{
    /***************************************************************************************
     ** GET
     ***************************************************************************************/
    public function get(Order $order)
    {
        $order->load(['orderItems.product', 'orderPurchase']);

        return $this->success(new OrderResource($order));
    }

    /***************************************************************************************
     ** POST
     ***************************************************************************************/
    public function create(OrderRequest $request, PaymentHandler $payment)
    {
        $customer = $this->getCustomer($request->order);

        $cart = Cart::byToken($request->order['cart_token'])->firstOrFail();

        if ($cart->total > 0) {
            $purchase = $payment->purchase($request->order, $request->stripe, $customer);
        } else {
            $purchase = OrderPurchase::makeFreePurchase($request->order, $customer);
        }

        event(new NewOrderPurchase($purchase));

        $order = \R64\Checkout\Facades\Order::getClassName()::makeOne($purchase, $request->order);
        $order->load(['orderItems.product', 'orderPurchase']);

        event(new NewOrder($order));

        return $this->success(new OrderResource($order));
    }

    private function getCustomer($order)
    {
        $customer = auth()->user();

        if (is_null($customer)) {
            $customer = new GuestCustomer(
                $order['customer_email'],
                $order['billing_first_name'],
                $order['billing_last_name']
            );
        }

        return $customer;
    }
}
