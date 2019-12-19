<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use R64\Checkout\Models\Coupon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'code' => Str::random(5)
    ];
});
