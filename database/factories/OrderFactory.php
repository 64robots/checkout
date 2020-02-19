<?php

use Faker\Generator as Faker;
use R64\Checkout\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'cart_id' => function () {
            return factory(R64\Checkout\Models\Cart::class)->create()->id;
        },
        'items_total' => $faker->numberBetween(1000, 10000),
        'customer_email' => $faker->unique()->safeEmail,
        'shipping_first_name' => $faker->catchPhrase,
        'shipping_last_name' => $faker->catchPhrase,
        'shipping_address_line1' => $faker->catchPhrase,
        'shipping_address_line2' => $faker->catchPhrase,
        'shipping_address_city' => $faker->catchPhrase,
        'shipping_address_region' => $faker->catchPhrase,
        'shipping_address_zipcode' => $faker->catchPhrase,
        'shipping_address_phone' => $faker->catchPhrase,
        'billing_address_line1' => $faker->catchPhrase,
        'billing_address_line2' => $faker->catchPhrase,
        'billing_address_city' => $faker->catchPhrase,
        'billing_address_region' => $faker->catchPhrase,
        'billing_address_zipcode' => $faker->catchPhrase,
        'billing_address_phone' => $faker->catchPhrase,
        'status' => $faker->catchPhrase,
        'customer_notes' => $faker->text,
        'admin_notes' => $faker->text,
    ];
});
