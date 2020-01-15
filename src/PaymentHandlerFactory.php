<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\Customer;

class PaymentHandlerFactory
{
    /** @var string */
    private $paymentHandlerClass;

    /**
     * @param string $paymentHandlerClass
     */
    public function __construct(string $paymentHandlerClass)
    {
        $this->paymentHandlerClass = $paymentHandlerClass;
    }

    /**
     * @param array $order
     * @param array $stripeDetails
     * @param Customer $customer
     *
     * @return PaymentHandler
     */
    public function createHandler(array $order, array $stripeDetails, Customer $customer)
    {
        return new $this->paymentHandlerClass($order, $stripeDetails, $customer);
    }
}
