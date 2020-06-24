<?php

namespace R64\Checkout;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use R64\Checkout\Contracts\Customer;
use R64\Checkout\Contracts\Customer as CustomerContract;
use R64\Checkout\Contracts\PaymentHandler;
use R64\Checkout\Models\OrderPurchase;

class PaypalPaymentHandler implements PaymentHandler
{
    /** @var PayPalHttpClient */
    protected $client;

    /**
     * @param PayPalHttpClient $client
     */
    public function __construct(PayPalHttpClient $client)
    {
        $this->client = $client;
    }

    public function purchase(array $order, array $paymentDetails, Customer $customer)
    {
        $orderResponse = $this->getPaypalOrder($paymentDetails);

        if ($orderResponse->statusCode !== 200) {
            throw new PaymentException("Paypal Status Code {$orderResponse->statusCode}");
        }

        $total = $this->getAmount($order);

        if ($this->getPaypalTotal($orderResponse) !== $total) {
            throw new PaymentException("Paypal Amount Exception");
        }

        $captureResponse = $this->capture($paymentDetails);

        if ($captureResponse->statusCode !== 201) {
            throw new PaymentException("Paypal Capture failed");
        }

        return $this->recordPurchase($paymentDetails, $order, $orderResponse, $captureResponse, $customer);
    }

    protected function getAmount($order)
    {
        $cart = \R64\Checkout\Facades\Cart::getClassName()::byToken($order['cart_token'])->first();

        return intval($cart->total);
    }

    /**
     * @param \PayPalHttp\HttpResponse $response
     * @return int
     */
    protected function getPaypalTotal(\PayPalHttp\HttpResponse $response): int
    {
        return intval(floatval($response->result->purchase_units[0]->amount->value) * 100);
    }

    /**
     * @param array $paymentDetails
     * @return \PayPalHttp\HttpResponse
     */
    protected function getPaypalOrder(array $paymentDetails): \PayPalHttp\HttpResponse
    {
        $response = $this->client->execute(new OrdersGetRequest($paymentDetails['order_id']));

        return $response;
    }

    /**
     * @param \PayPalHttp\HttpResponse $response
     * @return string
     */
    protected function getPaypalPayerEmail(\PayPalHttp\HttpResponse $response): string
    {
        return $response->result->payer->email_address;
    }

    /**
     * @param \PayPalHttp\HttpResponse $response
     * @return string
     */
    protected function getPaypalPayerId(\PayPalHttp\HttpResponse $response): string
    {
        return $response->result->payer->payer_id;
    }

    /**
     * @param \PayPalHttp\HttpResponse $response
     * @return string
     */
    protected function getPaypalCaptureId(\PayPalHttp\HttpResponse $response): string
    {
        return $response->result->id;
    }

    /**
     * @param array $paymentDetails
     *
     * @return \PayPalHttp\HttpResponse
     */
    protected function capture(array $paymentDetails)
    {
        $request = new AuthorizationsCaptureRequest($paymentDetails['authorization_id']);
        $request->body = "{}";
        return $this->client->execute($request);
    }

    protected function recordPurchase(array $paymentDetails, array $order, $orderResponse, $captureResponse, CustomerContract $customer)
    {
        $customerId = \R64\Checkout\Facades\Customer::getForeignKey();

        return OrderPurchase::makeOne([
            $customerId => $customer->getId(),
            'payment_processor' => PaymentHandlerFactory::PAYPAL,
            'order_data' => $order,
            'email' => $this->getPaypalPayerEmail($orderResponse),
            'amount' => $this->getPaypalTotal($orderResponse),
            'paypal_order_id' => $paymentDetails['order_id'],
            'paypal_authorization_id' => $paymentDetails['authorization_id'],
            'paypal_capture_id' => $this->getPaypalCaptureId($captureResponse),
            'paypal_payer_id' => $this->getPaypalPayerId($orderResponse),
        ], $customer);
    }
}
