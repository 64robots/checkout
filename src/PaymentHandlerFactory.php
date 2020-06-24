<?php

namespace R64\Checkout;

use Illuminate\Contracts\Container\Container;
use R64\Checkout\Contracts\PaymentHandler;
use R64\Checkout\Http\Requests\JsonFormRequest;

class PaymentHandlerFactory
{
    const PAYPAL = 'paypal';
    const STRIPE = 'stripe';

    /**
     * @param JsonFormRequest $request
     *
     * @return PaymentHandler
     */
    public static function createFromRequest(JsonFormRequest $request)
    {
        if ($request->has(static::STRIPE)) {
            return app(StripePaymentHandler::class);
        } else if ($request->has(static::PAYPAL)) {
            return app(PaypalPaymentHandler::class);
        }
    }
}
