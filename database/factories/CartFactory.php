<?php

namespace R64\Database\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use R64\Checkout\Helpers\Token;
use R64\Checkout\Models\CartItem;
use R64\Checkout\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    use WithFaker;

    protected $model = \R64\Checkout\Models\Cart::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory()->create()->id,
            'items_subtotal' => $this->faker->numberBetween(100, 200),
            'total' => $this->faker->numberBetween(400, 1000),
            'ip_address' => $this->faker->ipv4,
            'token' => Token::generate()->toString(),
            'shipping' => $this->faker->numberBetween(1000, 10000),
            'customer_email' => $this->faker->email,
            'shipping_first_name' => $this->faker->firstName,
            'shipping_last_name' => $this->faker->lastName,
            'shipping_address_line1' => $this->faker->streetAddress,
            'shipping_address_line2' => $this->faker->secondaryAddress,
            'shipping_address_city' => $this->faker->city,
            'shipping_address_region' => $this->faker->state,
            'shipping_address_zipcode' => $this->faker->postcode,
            'shipping_address_phone' => $this->faker->phoneNumber,
            'billing_first_name' => $this->faker->firstName,
            'billing_last_name' => $this->faker->lastName,
            'billing_address_line1' => $this->faker->streetAddress,
            'billing_address_line2' => $this->faker->secondaryAddress,
            'billing_address_city' => $this->faker->city,
            'billing_address_region' => $this->faker->state,
            'billing_address_zipcode' => $this->faker->postcode,
            'billing_address_phone' => $this->faker->phoneNumber
        ];
    }

    public function withProducts()
    {
        return $this->afterCreating(function () {

        });
    }
}
