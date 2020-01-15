<?php

namespace R64\Checkout;

use Illuminate\Support\Arr;
use R64\Checkout\Contracts\PaymentHandler as PaymentHandlerContract;
use R64\Checkout\Contracts\Customer as CustomerContract;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\OrderPurchase;
use R64\Stripe\Objects\Customer as StripeCustomer;
use R64\Stripe\PaymentProcessor;

class PaymentHandler implements PaymentHandlerContract
{
    /** @var array */
    private $order;

    /** @var array */
    private $stripeDetails;

    /** @var CustomerContract */
    private $customer;

    /** @var PaymentProcessor */
    private $processor;

    /**
     * @param array            $order
     * @param array            $stripeDetails
     * @param CustomerContract $customer
     */
    public function __construct(array $order, array $stripeDetails, CustomerContract $customer)
    {
        $this->order = $order;
        $this->stripeDetails = $stripeDetails;
        $this->customer = $customer;

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

    public function getOrCreateCustomer()
    {
        $orderPurchase = OrderPurchase::where('customer_id', $this->customer->getId())->first();

        if ($orderPurchase) {
            $customer = $this->processor->getCustomer($orderPurchase->stripe_customer_id);
            return $customer;
        }

        $email = !empty($this->order['customer_email']) ? $this->order['customer_email'] : $this->customer->getEmail();

        $customer = $this->processor->createCustomer([
            'description' => '64robots checkout ' . $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            'source' => $this->stripeDetails['token'],
            'email' => $email,
            'metadata' => [
                'first_name' => $this->customer->getFirstName(),
                'last_name' => $this->customer->getLastName(),
                'email' => $email
            ],
        ]);

        if (!$this->processor->attemptSuccessful()) {
            abort(400, $this->processor->getErrorMessage());
        }
        return $customer;
    }

    public function makePaymentAttempt(StripeCustomer $customer, array $stripeDetails)
    {
        return $this->chargeCard($customer, $stripeDetails);
    }

    public function chargeCard(StripeCustomer $customer, array $stripeDetails)
    {
        $card = $this->createCard($customer, $stripeDetails);

        $charge = $this->processor->createCharge([
            'customer' => $customer->id,
            'amount' => $this->getAmount(),
            'currency' => 'USD',
            'source' => $card->id,
        ]);
        if (! $this->processor->attemptSuccessful()) {
            abort(400, $this->processor->getErrorMessage());
        }

        return $charge;
    }

    public function createCard(StripeCustomer $customer, array $stripeDetails)
    {
        $card = $this->processor->createCard([
            'customer' => $customer->id,
            'token' => Arr::get($stripeDetails, 'token'),
        ]);

        if (! $this->processor->attemptSuccessful()) {
            abort(400, $this->processor->getErrorMessage());
        }

        return $card;
    }

    public function getAmount()
    {
        $cart = Cart::byToken($this->order['cart_token'])->first();

        return $cart->calculateTotal(Arr::get($this->order, 'shipping_id'));
    }

    public function recordPurchase(StripeCustomer $stripeCustomer , $paymentResponse)
    {
        return OrderPurchase::makeOne($this->customer, [
            'customer_id' => $this->customer->getId(),
            'order_data' => $this->order,
            'email' => $stripeCustomer->email,
            'amount' => $paymentResponse->amount,
            'card_type' => $paymentResponse->card->brand,
            'card_last4' => $paymentResponse->card->last4,
            'stripe_customer_id' => $stripeCustomer->id,
            'stripe_card_id' => $paymentResponse->card_id,
            'stripe_charge_id' => $paymentResponse->id
        ]);
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
