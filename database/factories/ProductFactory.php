<?php

namespace R64\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use R64\Checkout\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'price' => $this->faker->numberBetween(100, 400),
            'name' => $this->faker->name
        ];
    }
}
