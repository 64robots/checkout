<?php

use R64\Checkout\Helpers\Token;
use Faker\Generator as Faker;
use R64\Checkout\Models\CartItem;

$factory->define(CartItem::class, function (Faker $faker) {
    return [
        'cart_id' => function () {
            return factory(R64\Checkout\Models\Cart::class)->create()->id;
        },
        'product_id' => function () {
            return factory(R64\Checkout\Models\Product::class)->create()->id;
        },
        'price' => $faker->numberBetween(200, 400),
        'quantity' => $faker->numberBetween(1, 10),
        'token' => Token::generate(),
    ];
});
