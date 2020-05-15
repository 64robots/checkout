<?php

namespace Tests\Controllers\Checkout;

use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\CartItem;
use R64\Checkout\Models\Coupon;
use R64\Checkout\Models\Product;
use R64\Checkout\Tests\TestCase;

class CartCouponControllerTest extends TestCase
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
     * PUT /api/carts/{cart}/coupon-code
     */
    public function discount_code_discounts_the_total_price()
    {
        $cart = factory(Cart::class)->create(['shipping' => 0]);
        $product = factory(Product::class)->create(['price' => 100000]);
        CartItem::makeOne($cart, ['product_id' => $product->id]);

        $coupon = factory(Coupon::class)->state('$10off')->create();

        $response = $this->json('PUT', "/api/carts/{$cart->token}/coupon-code", [
            'coupon_code' => $coupon->code,
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => $this->cartStructure,
            ]);

        $response = json_decode($response->getContent(), true)['data'];

        $this->assertEquals('10.00', $response['discount']);
        $this->assertEquals('1,000.00', $response['items_subtotal']);
        $this->assertEquals('990.00', $response['total']);
    }

    /**
     * @test
     * DELETE /api/carts/{cart}/coupon-code
     */
    public function removing_discount_code_removes_discount_from_total()
    {
        $cart = factory(Cart::class)->create(['shipping' => 0]);
        $product = factory(Product::class)->create(['price' => 100000]);
        CartItem::makeOne($cart, ['product_id' => $product->id]);

        $coupon = factory(Coupon::class)->state('$10off')->create();

        $this->json('PUT', "/api/carts/{$cart->token}/coupon-code", [
            'coupon_code' => $coupon->code,
        ]);

        $this->assertDatabaseHas('carts', [
            'token' => $cart->token,
            'coupon_id' => $coupon->id
        ]);

        $response = $this->json('DELETE', "/api/carts/{$cart->token}/coupon-code");

        $response = json_decode($response->getContent(), true)['data'];

        $this->assertEquals('0.00', $response['discount']);
        $this->assertEquals('1,000.00', $response['items_subtotal']);
        $this->assertEquals('1,000.00', $response['total']);

        $this->assertDatabaseMissing('carts', [
            'token' => $cart->token,
            'coupon_id' => $coupon->id
        ]);
    }
}
