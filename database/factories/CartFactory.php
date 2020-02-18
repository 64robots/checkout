<?php

use R64\Checkout\Helpers\Token;
use R64\Checkout\Models\CartItem;
use Faker\Generator as Faker;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\Customer;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'customer_id' => function () {
            return factory(Customer::class)->create()->id;
        },
        'items_subtotal' => $faker->numberBetween(100, 200),
        'total' => $faker->numberBetween(400, 1000),
        'ip_address' => $faker->ipv4,
        'token' => Token::generate()->toString(),
        'shipping' => $faker->numberBetween(1000, 10000),
        'customer_email' => $faker->email,
        'shipping_first_name' => $faker->firstName,
        'shipping_last_name' => $faker->lastName,
        'shipping_address_line1' => $faker->streetAddress,
        'shipping_address_line2' => $faker->secondaryAddress,
        'shipping_address_city' => $faker->city,
        'shipping_address_region' => $faker->state,
        'shipping_address_zipcode' => $faker->postcode,
        'shipping_address_phone' => $faker->phoneNumber,
        'billing_first_name' => $faker->firstName,
        'billing_last_name' => $faker->lastName,
        'billing_address_line1' => $faker->streetAddress,
        'billing_address_line2' => $faker->secondaryAddress,
        'billing_address_city' => $faker->city,
        'billing_address_region' => $faker->state,
        'billing_address_zipcode' => $faker->postcode,
        'billing_address_phone' => $faker->phoneNumber
    ];
});

$factory->state(Cart::class, 'with_product', []);
$factory->afterCreatingState(Cart::class, 'with_product', function (Cart $cart) {
    factory(CartItem::class)->create([
        'cart_id' => $cart->id,
    ]);
});
