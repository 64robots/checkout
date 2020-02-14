<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\Cart::class;
    }
}
