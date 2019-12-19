<?php

use R64\Checkout\Helpers\Token;
use R64\Checkout\Models\CartItem;
use Faker\Generator as Faker;
use R64\Checkout\Models\Cart;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'items_subtotal' => $faker->numberBetween(100, 200),
        'total' => $faker->numberBetween(400, 1000),
        'ip_address' => $faker->ipv4,
        'token' => Token::generate(),
    ];
});

$factory->state(Cart::class, 'with_product', []);
$factory->afterCreatingState(Cart::class, 'with_product', function (Cart $cart) {
    factory(CartItem::class)->create([
        'cart_id' => $cart->id,
    ]);
});

$factory->state(Cart::class, 'without_user', [
    'user_id' => null
]);
