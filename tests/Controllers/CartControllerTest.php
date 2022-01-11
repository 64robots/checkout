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
        $response = $this->json('POST', '/api/carts', [])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $cart = json_decode($response->getContent(), true)['data'];

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
        $product = Product::factory()->create();

        dd($product);
        $response = $this->json('POST', '/api/carts', ['product_id' => $product->id]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $cart = json_decode($response->getContent(), true)['data'];

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

        $response = $this->json('PUT', "/api/carts/{$cart->token}", [
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
            ]);

        $response = json_decode($response->getContent(), true)['data'];

        $this->assertEquals($response['shipping_first_name'], $response['billing_first_name']);
        $this->assertEquals($response['shipping_last_name'], $response['billing_last_name']);
        $this->assertEquals($response['shipping_address_line1'], $response['billing_address_line1']);
        $this->assertEquals($response['shipping_address_line2'], $response['billing_address_line2']);
        $this->assertEquals($response['shipping_address_city'], $response['billing_address_city']);
        $this->assertEquals($response['shipping_address_region'], $response['billing_address_region']);
        $this->assertEquals($response['shipping_address_zipcode'], $response['billing_address_zipcode']);
        $this->assertEquals($response['shipping_address_phone'], $response['billing_address_phone']);
    }

    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function billing_information_is_not_the_same_as_shipping_when_billing_same_is_false()
    {
        $cart = factory(Cart::class)->create(['billing_same' => false]);

        $response = $this->json('PUT', "/api/carts/{$cart->token}", [
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
            ]);

        $response = json_decode($response->getContent(), true)['data'];

        $this->assertNull($response['billing_first_name']);
        $this->assertNull($response['billing_last_name']);
        $this->assertNull($response['billing_address_line1']);
        $this->assertNull($response['billing_address_line2']);
        $this->assertNull($response['billing_address_city']);
        $this->assertNull($response['billing_address_region']);
        $this->assertNull($response['billing_address_zipcode']);
        $this->assertNull($response['billing_address_phone']);
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

        $response = $this->actingAs($customer)
            ->json('POST', '/api/carts', [])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $cart = json_decode($response->getContent(), true)['data'];

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

        $response = $this->actingAs($customer)
            ->json('POST', '/api/carts', ['product_id' => $product->id])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $cart = json_decode($response->getContent(), true)['data'];

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
