<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use R64\Checkout\Models\CheckoutProduct;
use Faker\Generator as Faker;

$factory->define(CheckoutProduct::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(100, 400),
        'description' => $faker->sentence,
        'image' => $faker->imageUrl(),
        'name' => $faker->name
    ];
});
