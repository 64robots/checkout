<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Http\Requests\OrderRequest;
use R64\Checkout\Http\Resources\OrderResource;
use R64\Checkout\Models\Order;

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
        return $this->success(new OrderResource($order));
    }

    /***************************************************************************************
     ** POST
     ***************************************************************************************/
    public function create(OrderRequest $request)
    {
        $order = Order::makeOne($request->validated());

        $order->load('order_items');

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
