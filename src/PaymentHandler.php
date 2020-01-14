<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\PaymentHandler as PaymentHandlerContract;
use R64\Stripe\PaymentProcessor;

class PaymentHandler implements PaymentHandlerContract
{
    /** @var array */
    private $order;

    /** @var array */
    private $stripeDetails;

    /** @var PaymentProcessor */
    private $processor;

    /**
     * @param array $order
     * @param array $stripeDetails
     */
    public function __construct(array $order, array $stripeDetails)
    {
        $this->order = $order;
        $this->stripeDetails = $stripeDetails;

        $this->setup();
    }

    public function purchase()
    {
        // Create Customer
        $customer = $this->getOrCreateCustomer();

        // Create Transaction
        $paymentReponse = $this->makePaymentAttempt($customer, $this->stripeDetails);

        // Record Purchase
        $purchase = $this->recordPurchase($customer, $paymentReponse);

        return $purchase;
    }

    /***************************************************************************************
    ** SETUP
    ***************************************************************************************/

    public function setup()
    {
        $this->checkReady();
        $this->setProcessor();
    }

    public function setProcessor()
    {
        $this->processor = app(PaymentProcessor::class);
    }

    /**
     * Make Sure We Have Essentials To Make the Charge.
     */
    public function checkReady()
    {
        if (! $this->stripeDetails['token']) {
            abort(500, 'Internal Error: E902');
        }
    }
}
