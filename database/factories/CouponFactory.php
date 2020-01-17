<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use R64\Checkout\Models\Coupon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Coupon::class, function (Faker $faker) {
    $randomNumber = $faker->numberBetween(0, 2);
    return [
        'name' => ['$10 off', '$20 off', '$30 off', '10% off', 'FREE'][$randomNumber],
        'code' => ['$10OFF', '$20OFF', '$30OFF', '10%OFF', 'FREE'][$randomNumber],
        'discount' => [1000, 2000, 3000, 1000, 10000][$randomNumber],
        'percentage' => [false, false, false, true, true][$randomNumber],
        'active' => true
    ];
});

$factory->state(Coupon::class, '$10off', [
    'name' => '$10 off',
    'code' => '$10OFF',
    'discount' => 1000,
    'percentage' => false
]);

$factory->state(Coupon::class, '$20off', [
    'name' => '$20 off',
    'code' => '$20OFF',
    'discount' => 2000,
    'percentage' => false
]);


$factory->state(Coupon::class, '$30off', [
    'name' => '$30 off',
    'code' => '$30OFF',
    'discount' => 3000,
    'percentage' => false
]);

$factory->state(Coupon::class, '10%off', [
    'name' => '10% off',
    'code' => '10%OFF',
    'discount' => 1000,
    'percentage' => true
]);

$factory->state(Coupon::class, 'free', [
    'name' => 'free',
    'code' => 'free',
    'discount' => 10000,
    'percentage' => true
]);
