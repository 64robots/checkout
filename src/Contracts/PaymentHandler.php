<?php

namespace R64\Checkout\Contracts;

interface PaymentHandler
{
    public function purchase(array $order, array $paymentDetails, Customer $customer);
}
