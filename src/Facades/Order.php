<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class Order extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\Order::class;
    }
}
