<?php

use Faker\Generator as Faker;
use R64\Checkout\Models\OrderItem;

$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'product_id' => function () {
            return factory(R64\Checkout\Models\CheckoutProduct::class)->create()->id;
        },
        'cart_item_id' => function () {
            return factory(R64\Checkout\Models\CartItem::class)->create()->id;
        },
        'name' => $faker->catchPhrase,
    ];
});
