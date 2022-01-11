<?php

namespace R64\Checkout\Tests\Controllers;

use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Models\Cart;
use R64\Checkout\StripeMockHandler;
use R64\Checkout\Tests\TestCase;
use R64\Stripe\StripeInterface;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, InteractsWithExceptionHandling;

    private $orderStructure = [
        'token',
        'order_number',
        'customer_email',
        'items_total',
        'shipping',
        'total',
        'tax_rate',
        'tax',
        'discount',
        'currency',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address_line1',
        'shipping_address_line2',
        'shipping_address_city',
        'shipping_address_region',
        'shipping_address_zipcode',
        'shipping_address_phone',
        'billing_first_name',
        'billing_last_name',
        'billing_address_line1',
        'billing_address_line2',
        'billing_address_city',
        'billing_address_region',
        'billing_address_zipcode',
        'billing_address_phone',
        'status',
        'customer_notes',
        'admin_notes',
        'order_items' => [
            '*' => [
                'name',
                'price',
                'quantity'
            ]
        ],
        'order_purchase' => [
            'amount',
            'card_type',
            'card_last4'
        ],
        'created_at'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(StripeInterface::class, function ($app) {
            $options['secret_key'] = $app['config']->get('stripe.secret');
            $options['stripe_connect_id'] = $app['config']->get('stripe.connect_id') ?? $app->request->get('stripe_connect_id');
            $options['skip_stripe_connect'] = $app['config']->get('stripe.skip_connect') ?? $app->request->get('skip_stripe_connect', true);

            return new StripeMockHandler($options);
        });
    }

    /**
     * @test
     * POST /api/orders
     */
    public function anyone_can_create_an_order()
    {
        $cart = Cart::factory()->withProducts()->create();

        $response = $this->json('POST', '/api/orders', [
            'stripe' => [
                'token' => 'random token'
            ],
            'order' => [
                'cart_token' => $cart->token,
                'customer_email' => 'email@gmail.com',
                'shipping_first_name' => 'First name',
                'shipping_last_name' => 'Last name',
                'shipping_address_line1' => 'Street 1',
                'shipping_address_line2' => 'Line 2',
                'shipping_address_city' => 'Beverly Hills',
                'shipping_address_region' => 'California',
                'shipping_address_zipcode' => '90210',
                'shipping_address_phone' => '123321123',
                'billing_first_name' => 'First name',
                'billing_last_name' => 'Last name',
                'billing_address_line1' => 'Street 1',
                'billing_address_line2' => 'Line 2',
                'billing_address_city' => 'Beverly Hills',
                'billing_address_region' => 'California',
                'billing_address_zipcode' => '90210',
                'billing_address_phone' => '123321123',
            ]
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->orderStructure
            ]);

        $cart = $this->responseToData($this->json('GET', '/api/carts/' . $cart->token));
        $order = $this->responseToData($response);

        $this->assertEquals('email@gmail.com', $order['customer_email']);
        $this->assertEquals('First name', $order['shipping_first_name']);
        $this->assertEquals('Last name', $order['shipping_last_name']);
        $this->assertEquals('Street 1', $order['shipping_address_line1']);
        $this->assertEquals('Line 2', $order['shipping_address_line2']);
        $this->assertEquals('Beverly Hills', $order['shipping_address_city']);
        $this->assertEquals('California', $order['shipping_address_region']);
        $this->assertEquals('90210', $order['shipping_address_zipcode']);
        $this->assertEquals('123321123', $order['shipping_address_phone']);
        $this->assertEquals('First name', $order['billing_first_name']);
        $this->assertEquals('Last name', $order['billing_last_name']);
        $this->assertEquals('Street 1', $order['billing_address_line1']);
        $this->assertEquals('Line 2', $order['billing_address_line2']);
        $this->assertEquals('Beverly Hills', $order['billing_address_city']);
        $this->assertEquals('California', $order['billing_address_region']);
        $this->assertEquals('90210', $order['billing_address_zipcode']);
        $this->assertEquals('123321123', $order['billing_address_phone']);

        $this->assertEquals(1, $order['order_number']);
        $this->assertEquals($cart['items_subtotal'], $order['items_total']);
        $this->assertEquals($cart['shipping'], $order['shipping']);
        $this->assertEquals($cart['total'], $order['total']);
        $this->assertEquals($cart['tax'], $order['tax']);
        $this->assertEquals($cart['tax_rate'], $order['tax_rate']);
        $this->assertCount(count($cart['cart_items']), $order['order_items']);

        $this->assertEquals($cart['total'], $order['order_purchase']['amount']);
    }

    /**
     * @test
     * GET /api/orders/{order-token}
     */
    public function anyone_can_get_an_order_by_token()
    {
        $cart = Cart::factory()->withProducts()->create();

        $response = $this->json('POST', '/api/orders', [
            'stripe' => [
                'token' => 'random token'
            ],
            'order' => [
                'cart_token' => $cart->token,
                'customer_email' => 'email@gmail.com',
                'shipping_first_name' => 'First name',
                'shipping_last_name' => 'Last name',
                'shipping_address_line1' => 'Street 1',
                'shipping_address_line2' => 'Line 2',
                'shipping_address_city' => 'Beverly Hills',
                'shipping_address_region' => 'California',
                'shipping_address_zipcode' => '90210',
                'shipping_address_phone' => '123321123',
                'billing_first_name' => 'First name',
                'billing_last_name' => 'Last name',
                'billing_address_line1' => 'Street 1',
                'billing_address_line2' => 'Line 2',
                'billing_address_city' => 'Beverly Hills',
                'billing_address_region' => 'California',
                'billing_address_zipcode' => '90210',
                'billing_address_phone' => '123321123',
            ]
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->orderStructure
            ]);

        $order = $this->responseToData($response);

        $response = $this
            ->json('GET', '/api/orders/' . $order['token'])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->orderStructure
            ]);

        $orderResponse = $this->responseToData($response);

        $this->assertEquals($order, $orderResponse);
    }
}
