<?php

namespace R64\Checkout\Http\Controllers;

use Illuminate\Validation\ValidationException;
use R64\Checkout\Events\NewOrder;
use R64\Checkout\Events\NewOrderPurchase;
use R64\Checkout\Http\Requests\OrderRequest;
use R64\Checkout\Http\Resources\OrderResource;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Order;
use R64\Checkout\Models\OrderPurchase;
use R64\Checkout\GuestCustomer;
use R64\Checkout\Contracts\PaymentHandler;
use R64\Checkout\PaymentException;
use R64\Checkout\PaymentHandlerFactory;

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
    public function create(OrderRequest $request)
    {
        $customer = $this->getCustomer($request->order);

        $cart = Cart::byToken($request->order['cart_token'])->firstOrFail();

        try {
            $purchase = $this->createPurchase($request, $cart, $customer);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'payment' => $e->getMessage()
            ]);
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

    private function pay(OrderRequest $request, $customer)
    {
        $paymentHandler = PaymentHandlerFactory::createFromRequest($request);

        if ($request->isStripe()) {
            return $paymentHandler->purchase($request->order, $request->stripe, $customer);
        } else if ($request->isPaypal()) {
            return $paymentHandler->purchase($request->order, $request->paypal, $customer);
        }

        throw new PaymentException("Missing stripe or paypal request information");
    }

    /**
     * @param OrderRequest $request
     * @param $cart
     * @param $customer
     * @return OrderPurchase
     */
    protected function createPurchase(OrderRequest $request, $cart, $customer): OrderPurchase
    {
        if ($cart->total === 0) {
            return OrderPurchase::makeFreePurchase($request->order, $customer);
        }

        return $this->pay($request, $customer);
    }
}
