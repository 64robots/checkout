<?php

namespace R64\Checkout\Database\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    use WithFaker;

    protected $model = \R64\Checkout\Models\Order::class;

    public function definition()
    {
        return [
            'cart_id' => function () {
                return \R64\Checkout\Models\Cart::factory()->create()->id;
            },
            'items_total' => $this->faker->numberBetween(1000, 10000),
            'customer_email' => $this->faker->unique()->safeEmail,
            'shipping_first_name' => $this->faker->catchPhrase,
            'shipping_last_name' => $this->faker->catchPhrase,
            'shipping_address_line1' => $this->faker->catchPhrase,
            'shipping_address_line2' => $this->faker->catchPhrase,
            'shipping_address_city' => $this->faker->catchPhrase,
            'shipping_address_region' => $this->faker->catchPhrase,
            'shipping_address_zipcode' => $this->faker->catchPhrase,
            'shipping_address_phone' => $this->faker->catchPhrase,
            'billing_address_line1' => $this->faker->catchPhrase,
            'billing_address_line2' => $this->faker->catchPhrase,
            'billing_address_city' => $this->faker->catchPhrase,
            'billing_address_region' => $this->faker->catchPhrase,
            'billing_address_zipcode' => $this->faker->catchPhrase,
            'billing_address_phone' => $this->faker->catchPhrase,
            'status' => $this->faker->catchPhrase,
            'customer_notes' => $this->faker->text,
            'admin_notes' => $this->faker->text,
        ];
    }
}
