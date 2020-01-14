<?php

namespace R64\Checkout;

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
     *
     * @return PaymentHandler
     */
    public function createHandler(array $order, array $stripeDetails)
    {
        return new $this->paymentHandlerClass($order, $stripeDetails);
    }
}
