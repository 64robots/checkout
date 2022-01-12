<?php

namespace R64\Checkout\Database\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    use WithFaker;

    protected $model = \R64\Checkout\Models\OrderItem::class;

    public function definition()
    {
        return [
            'product_id' => function () {
                return \R64\Checkout\Models\Product::factory()->create()->id;
            },
            'cart_item_id' => function () {
                return \R64\Checkout\Models\CartItem::factory()->create()->id;
            },
            'name' => $this->faker->word,
        ];
    }
}
