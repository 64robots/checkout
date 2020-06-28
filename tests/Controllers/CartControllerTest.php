<?php

namespace Tests\Controllers\Checkout;

use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Customer;
use R64\Checkout\Models\Product;
use R64\Checkout\Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private $cartStructure = [
        'cart_token',
        'items_subtotal',
        'tax_rate',
        'tax',
        'total',
        'discount',
        'cart_items' => [
            '*' => [
                'cart_item_token',
                'price',
                'quantity',
                'customer_note',
                'product' => [
                    'name',
                    'image',
                ],
            ],
        ],
    ];

    /**
     * @test
     * GET /api/cart/{cart}
     */
    public function anybody_can_view_cart_by_token()
    {
        $cart = factory(Cart::class)->state('with_product')->create();

        $this
            ->json('GET', "/api/carts/{$cart->token}")
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);
    }

    /**
     * @test
     * POST /api/carts
     */
    public function anybody_can_create_an_empty_cart()
    {
        $cart = $this->json('POST', '/api/carts', [])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertCount(0, $cart['cart_items']);
        $this->assertDatabaseHas('carts', [
            'token' => $cart['cart_token'],
            'total' => 0,
            'items_subtotal' => 0,
            'tax' => 0,
            'tax_rate' => 0,
        ]);
    }

    /**
     * @test
     * POST /api/carts
     */
    public function anybody_can_create_cart_with_one_item()
    {
        $product = factory(Product::class)->create();

        $cart = $this->json('POST', '/api/carts', ['product_id' => $product->id])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertCount(1, $cart['cart_items']);
        $this->assertDatabaseHas('carts', [
            'token' => $cart['cart_token'],
            'items_subtotal' => $product->getPrice(),
        ]);
    }

    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function anybody_can_update_a_cart()
    {
        $cart = factory(Cart::class)->state('with_product')->create();

        $this->json('PUT', "/api/carts/{$cart->token}", [
            'customer_notes' => "here we go, it's a note",
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $this->assertDatabaseHas('carts', [
            'customer_notes' => "here we go, it's a note",
        ]);
    }

    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function billing_information_is_the_same_as_shipping_by_default()
    {
        $cart = factory(Cart::class)->create();

        $cartResponse = $this->json('PUT', "/api/carts/{$cart->token}", [
            'shipping_first_name' => "first name",
            'shipping_last_name' => "last name",
            'shipping_address_line1' => "line 1",
            'shipping_address_line2' => "line 2",
            'shipping_address_city' => "city",
            'shipping_address_region' => "region",
            'shipping_address_zipcode' => "zipcode",
            'shipping_address_phone' => "123123",
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertEquals($cartResponse['shipping_first_name'], $cartResponse['billing_first_name']);
        $this->assertEquals($cartResponse['shipping_last_name'], $cartResponse['billing_last_name']);
        $this->assertEquals($cartResponse['shipping_address_line1'], $cartResponse['billing_address_line1']);
        $this->assertEquals($cartResponse['shipping_address_line2'], $cartResponse['billing_address_line2']);
        $this->assertEquals($cartResponse['shipping_address_city'], $cartResponse['billing_address_city']);
        $this->assertEquals($cartResponse['shipping_address_region'], $cartResponse['billing_address_region']);
        $this->assertEquals($cartResponse['shipping_address_zipcode'], $cartResponse['billing_address_zipcode']);
        $this->assertEquals($cartResponse['shipping_address_phone'], $cartResponse['billing_address_phone']);
    }

    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function billing_information_is_not_the_same_as_shipping_when_billing_same_is_false()
    {
        $cart = factory(Cart::class)->create(['billing_same' => false]);

        $cartResponse = $this->json('PUT', "/api/carts/{$cart->token}", [
            'shipping_first_name' => "first name",
            'shipping_last_name' => "last name",
            'shipping_address_line1' => "line 1",
            'shipping_address_line2' => "line 2",
            'shipping_address_city' => "city",
            'shipping_address_region' => "region",
            'shipping_address_zipcode' => "zipcode",
            'shipping_address_phone' => "123123",
            'billing_same' => false
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertNull($cartResponse['billing_first_name']);
        $this->assertNull($cartResponse['billing_last_name']);
        $this->assertNull($cartResponse['billing_address_line1']);
        $this->assertNull($cartResponse['billing_address_line2']);
        $this->assertNull($cartResponse['billing_address_city']);
        $this->assertNull($cartResponse['billing_address_region']);
        $this->assertNull($cartResponse['billing_address_zipcode']);
        $this->assertNull($cartResponse['billing_address_phone']);
    }

    /**
     * @test
     * DELETE /api/carts
     */
    public function anybody_can_delete_a_cart()
    {
        $cart = factory(Cart::class)->state('with_product')->create();

        $this->json('DELETE', "/api/carts/{$cart->token}")
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('carts', [
            'token' => $cart->token,
        ]);

        $this->assertSoftDeleted('cart_items', [
            'id' => $cart->cartItems()->withTrashed()->first()->id,
        ]);
    }

    /**
     * @test
     * POST /api/carts
     */
    public function customer_can_create_an_empty_cart()
    {
        $customer = factory(Customer::class)->create();

        $cart = $this->actingAs($customer)
            ->json('POST', '/api/carts', [])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertCount(0, $cart['cart_items']);
        $this->assertDatabaseHas('carts', [
            'token' => $cart['cart_token'],
            'customer_id' => $customer->id,
            'total' => 0,
            'items_subtotal' => 0,
        ]);
    }

    /**
     * @test
     * POST /api/carts
     */
    public function customer_can_create_cart_with_one_item()
    {
        $customer = factory(Customer::class)->create();
        $product = factory(Product::class)->create();

        $cart = $this->actingAs($customer)
            ->json('POST', '/api/carts', ['product_id' => $product->id])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ])
            ->decodeResponseJson('data');

        $this->assertCount(1, $cart['cart_items']);
        $this->assertDatabaseHas('carts', [
            'token' => $cart['cart_token'],
            'items_subtotal' => $product->getPrice(),
        ]);
    }

    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function customer_can_update_a_cart()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'email@email.com',
        ]);
        $cart = factory(Cart::class)->state('with_product')->create([
            'customer_id' => $customer->id,
        ]);

        $this->actingAs($customer, 'api')
            ->json('PUT', "/api/carts/{$cart->token}", [
                'customer_email' => 'new@email.com',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $this->assertDatabaseHas('carts', [
            'customer_email' => 'new@email.com',
        ]);
    }
}
