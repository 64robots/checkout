<?php

namespace R64\Checkout\Tests\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Product;
use R64\Checkout\Tests\TestCase;

class CartItemControllerTest extends TestCase
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

    private $cartItemStructure = [
        'cart_item_token',
        'price',
        'quantity',
        'customer_note',
        'product' => [
            'name',
            'image',
            'price'
        ]
    ];

    /**
     * @test
     * POST /api/carts/{cart}/cart-tiems
     */
    public function anyone_can_add_a_product_to_a_cart()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $this->json('POST', "/api/carts/{$cart->token}/cart-items", [
            'product_id' => $product->id,
            'quantity' => 5
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure
            ]);


        $this->assertDatabaseHas('cart_items', ['cart_id' => $cart->id, 'product_id' => $product->id]);
    }

    /**
     * @test
     * PUT /api/cart-items/{cartItem}
     */
    public function anyone_can_update_cart_item_quantity()
    {
        $cart = Cart::factory()->withProducts()->create();
        $cartItem = $cart->cartItems->first();

        $newQuantity = $cartItem->quantity + 10;

        $this->json('PUT', "api/cart-items/{$cartItem->token}", [
            'quantity' => $newQuantity
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure
            ]);

        $this->assertDatabaseHas('cart_items', ['id' => $cartItem->id, 'quantity' => $newQuantity]);
    }

    /**
     * @test
     * PUT /api/cart-items/{cartItem}
     */
    public function cart_total_changes_when_new_product_is_added_into_the_cart()
    {
        $this->withoutExceptionHandling();
        $cartResponse = $this->json('POST', "/api/carts")
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure
            ]);

        $cartResponse = json_decode($cartResponse->getContent(), true)['data'];

        $this->assertEquals('0.00', $cartResponse['items_subtotal']);
        $this->assertEquals('0.00', $cartResponse['total']);
        $this->assertEmpty($cartResponse['cart_items']);

        $product = Product::factory()->create(['price' => 1000]);

        $cartResponse = $this->json('POST', "/api/carts/${cartResponse['cart_token']}/cart-items", [
            'product_id' => $product->id,
            'quantity' => 2
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure
            ]);


        $cartResponse = json_decode($cartResponse->getContent(), true)['data'];

        $this->assertEquals("20.00", $cartResponse['items_subtotal']);
        $this->assertEquals("20.00", $cartResponse['total']);
        $this->assertCount(1, $cartResponse['cart_items']);
        $this->assertEquals("20.00", $cartResponse['cart_items'][0]['price']);
        $this->assertEquals("2", $cartResponse['cart_items'][0]['quantity']);
        $this->assertEquals("10.00", $cartResponse['cart_items'][0]['product']['price']);
    }

    /**
     * @test
     * DELETE /api/cart-items/{cartItem}
     */
    public function anyone_can_delete_cart_item()
    {
        $cart = Cart::factory()->withProducts()->create();
        $cartItem = $cart->cartItems->first();

        $this->json('DELETE', "/api/cart-items/{$cartItem->token}")
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('cart_items', [
            'id' => $cartItem->id,
        ]);
    }
}
