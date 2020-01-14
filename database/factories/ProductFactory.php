<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use R64\Checkout\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(100, 400),
        'name' => $faker->name
    ];
});
