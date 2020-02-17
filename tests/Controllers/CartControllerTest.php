<?php

namespace Tests\Controllers\Checkout;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Helpers\Price;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Coupon;
use R64\Checkout\Models\Customer;
use R64\Checkout\Models\Product;
use R64\Checkout\Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, InteractsWithDatabase;

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
                    'image'
                ]
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
        $product = factory(Product::class)->create();

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
            'items_subtotal' => $product->getPrice()
        ]);
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
            'email' => 'email@email.com'
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
            'customer_email' => 'new@email.com'
        ]);
    }
}
