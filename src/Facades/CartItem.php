<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class CartItem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \R64\Checkout\Contracts\CartItem::class;
    }
}
