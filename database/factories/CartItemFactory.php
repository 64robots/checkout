<?php

namespace R64\Checkout\Database\Factories;

use R64\Checkout\Models\Cart;
use R64\Checkout\Helpers\Token;
use R64\Checkout\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    use WithFaker;

    protected $model = \R64\Checkout\Models\CartItem::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => function () {
                return Cart::factory()->create()->id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'price' => $this->faker->numberBetween(200, 400),
            'quantity' => $this->faker->numberBetween(1, 10),
            'token' => Token::generate(),
        ];
    }
}
