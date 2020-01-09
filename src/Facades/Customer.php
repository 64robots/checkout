<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class Customer extends Facade
{
    public static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\Customer::class;
    }
}
