<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\PaymentHandler as PaymentHandlerContract;
use R64\Checkout\Contracts\Customer as CustomerContract;
use R64\Checkout\Models\OrderPurchase;
use R64\Stripe\Objects\Customer as StripeCustomer;
use R64\Stripe\PaymentProcessor;

class StripePaymentHandler implements PaymentHandlerContract
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

    public function purchase(array $order, array $paymentDetails, CustomerContract $customer)
    {
        if (!isset($paymentDetails['token'])) {
            throw new PaymentException("Stripe: Token is missing");
        }

        // Create Customer
        $stripeCustomer = $this->getOrCreateCustomer($order, $paymentDetails, $customer);

        // Create Transaction
        $paymentResponse = $this->makePaymentAttempt($order, $stripeCustomer);

        // Record Purchase
        return $this->recordPurchase($paymentResponse, $order, $customer, $stripeCustomer);
    }

    protected function getOrCreateCustomer(array $order, array $paymentDetails, CustomerContract $customer)
    {
        $orderPurchase = OrderPurchase::byEmail($customer->getEmail())->stripe()->first();

        if ($orderPurchase) {
            $stripeCustomer = $this->processor->getCustomer($orderPurchase->stripe_customer_id);

            if ($stripeCustomer) {
                return $stripeCustomer;
            }
        }

        $email = $order['customer_email'];
        $firstName = $order['billing_first_name'];
        $lastName = $order['billing_last_name'];

        $stripeCustomer = $this->processor->createCustomer([
            'description' => '64robots checkout ' . $firstName . ' ' . $lastName,
            'source' => $paymentDetails['token'],
            'email' => $email,
            'metadata' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email
            ],
        ]);

        if (!$this->processor->attemptSuccessful()) {
            throw new PaymentException("Stripe: " . $this->processor->getErrorMessage());
        }
        return $stripeCustomer;
    }

    protected function makePaymentAttempt(array $order, StripeCustomer $customer)
    {
        return $this->chargeCard($order, $customer);
    }

    protected function chargeCard(array $order, StripeCustomer $customer)
    {
        $charge = $this->processor->createCharge([
            'customer' => $customer->id,
            'amount' => $this->getAmount($order),
            'currency' => 'USD'
        ]);

        if (! $this->processor->attemptSuccessful()) {
            throw new PaymentException("Stripe: " . $this->processor->getErrorMessage());
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
            'payment_processor' => PaymentHandlerFactory::STRIPE,
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
