<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class Coupon extends Facade
{
    public static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\Coupon::class;
    }
}
