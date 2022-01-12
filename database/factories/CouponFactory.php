<?php

namespace R64\Checkout\Database\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    use WithFaker;

    protected $model = \R64\Checkout\Models\Coupon::class;

    public function definition()
    {
        $randomNumber = $this->faker->numberBetween(0, 2);
        return [
            'name' => ['$10 off', '$20 off', '$30 off', '10% off', 'FREE'][$randomNumber],
            'code' => ['$10OFF', '$20OFF', '$30OFF', '10%OFF', 'FREE'][$randomNumber],
            'discount' => [1000, 2000, 3000, 1000, 10000][$randomNumber],
            'percentage' => [false, false, false, true, true][$randomNumber],
            'active' => true
        ];
    }

    public function tenDollarsOff()
    {
        return $this->state([
            'name' => '$10 off',
            'code' => '$10OFF',
            'discount' => 1000,
            'percentage' => false
        ]);
    }

    public function twentyDollarsOff()
    {
        return $this->state([
            'name' => '$20 off',
            'code' => '$20OFF',
            'discount' => 2000,
            'percentage' => false
        ]);
    }

    public function thirtyDollarsOf()
    {
        return $this->state([
            'name' => '$30 off',
            'code' => '$30OFF',
            'discount' => 3000,
            'percentage' => false
        ]);
    }

    public function tenPercentOff()
    {
        return $this->state([
            'name' => '10% off',
            'code' => '10%OFF',
            'discount' => 1000,
            'percentage' => true
        ]);;
    }

    public function free()
    {
        return $this->state([
            'name' => 'free',
            'code' => 'free',
            'discount' => 10000,
            'percentage' => true
        ]);
    }
}
