<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class OrderItem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\OrderItem::class;
    }
}
