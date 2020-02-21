<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\PaymentHandler as PaymentHandlerContract;
use R64\Checkout\Contracts\Customer as CustomerContract;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\OrderPurchase;
use R64\Stripe\Objects\Customer as StripeCustomer;
use R64\Stripe\PaymentProcessor;

class PaymentHandler implements PaymentHandlerContract
{
    /** @var PaymentProcessor */
    protected $processor;

    /**
     * @param PaymentProcessor $processor
     */
    public function __construct(PaymentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function purchase(array $order, array $stripeDetails, CustomerContract $customer)
    {
        if (!isset($stripeDetails['token'])) {
            abort(500, 'Internal Error: E902');
        }

        // Create Customer
        $stripeCustomer = $this->getOrCreateCustomer($order, $stripeDetails, $customer);

        // Create Transaction
        $paymentResponse = $this->makePaymentAttempt($order, $stripeDetails, $stripeCustomer);

        // Record Purchase
        return $this->recordPurchase($paymentResponse, $order, $customer, $stripeCustomer);
    }

    protected function getOrCreateCustomer(array $order, array $stripeDetails, CustomerContract $customer)
    {
        $orderPurchase = OrderPurchase::where(\R64\Checkout\Facades\Customer::getForeignKey(), $customer->getId())->first();

        if ($orderPurchase) {
            return $this->processor->getCustomer($orderPurchase->stripe_customer_id);
        }

        $email = $order['customer_email'];
        $firstName = $order['billing_first_name'];
        $lastName = $order['billing_last_name'];

        $stripeCustomer = $this->processor->createCustomer([
            'description' => '64robots checkout ' . $firstName . ' ' . $lastName,
            'source' => $stripeDetails['token'],
            'email' => $email,
            'metadata' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email
            ],
        ]);

        if (!$this->processor->attemptSuccessful()) {
            abort(400, $this->processor->getErrorMessage());
        }
        return $stripeCustomer;
    }

    protected function makePaymentAttempt(array $order, array $stripeDetails, StripeCustomer $customer)
    {
        return $this->chargeCard($order, $stripeDetails, $customer);
    }

    public function createCard(array $stripeDetails, StripeCustomer $customer)
    {
        $card = $this->processor->createCard([
            'customer' => $customer->id,
            'token' => $stripeDetails['token'],
        ]);

        if (! $this->processor->attemptSuccessful()) {
            abort(400, $this->processor->getErrorMessage());
        }

        return $card;
    }

    protected function chargeCard(array $order, array $stripeDetails, StripeCustomer $customer)
    {
        $card = $this->createCard($stripeDetails, $customer);

        $charge = $this->processor->createCharge([
            'customer' => $customer->id,
            'amount' => $this->getAmount($order),
            'currency' => 'USD',
            'source' => $card->id
        ]);

        if (! $this->processor->attemptSuccessful()) {
            info($this->processor->getErrorMessage());
            abort(400, $this->processor->getErrorMessage());
        }

        return $charge;
    }

    protected function getAmount($order)
    {
        $cart = \R64\Checkout\Facades\Cart::getClassName()::byToken($order['cart_token'])->first();

        return $cart->total;
    }

    protected function recordPurchase($paymentResponse, array $order, CustomerContract $customer, StripeCustomer $stripeCustomer)
    {
        $customerId = \R64\Checkout\Facades\Customer::getForeignKey();

        return OrderPurchase::makeOne([
            $customerId => $customer->getId(),
            'order_data' => $order,
            'email' => $stripeCustomer->email,
            'amount' => $paymentResponse->amount,
            'card_type' => $paymentResponse->card->brand,
            'card_last4' => $paymentResponse->card->last4,
            'stripe_customer_id' => $stripeCustomer->id,
            'stripe_card_id' => $paymentResponse->card_id,
            'stripe_charge_id' => $paymentResponse->id
        ], $customer);
    }
}
