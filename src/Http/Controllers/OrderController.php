<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Events\NewOrder;
use R64\Checkout\Events\NewOrderPurchase;
use R64\Checkout\Http\Requests\OrderRequest;
use R64\Checkout\Http\Resources\OrderResource;
use R64\Checkout\Models\Customer;
use R64\Checkout\Models\Order;
use R64\Checkout\PaymentHandler;
use R64\Checkout\PaymentHandlerFactory;

class OrderController extends Controller
{
    /***************************************************************************************
     ** LIST
     ***************************************************************************************/
    public function list()
    {
        $orders = Order::byEmail(auth()->user()->email)->get();

        return $this->success(OrderResource::collection($orders));
    }

    /***************************************************************************************
     ** GET
     ***************************************************************************************/
    public function get(Order $order)
    {
        $order->load(['order_items.product', 'orderPurchase']);

        return $this->success(new OrderResource($order));
    }

    /***************************************************************************************
     ** POST
     ***************************************************************************************/
    public function create(OrderRequest $request, PaymentHandlerFactory $factory)
    {
        /** @var PaymentHandler $handler */
        $customer = auth()->user();
        $handler = $factory->createHandler($request->order, $request->stripe, $customer);
        $purchase = $handler->purchase();

        event(new NewOrderPurchase($purchase));

        $order = Order::makeOne($purchase, $request->order);
        $order->load(['order_items', 'orderPurchase']);

        event(new NewOrder($order));

        return $this->success(new OrderResource($order));
    }

    /***************************************************************************************
     ** PUT
     ***************************************************************************************/
    public function delete(Order $order)
    {
        $order->delete();

        return $this->success();
    }
}
