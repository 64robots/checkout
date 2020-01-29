<?php

namespace R64\Checkout\Facades;

use Illuminate\Support\Facades\Facade;

class AddressSearch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \R64\Checkout\Helpers\Address\AddressSearch::class;
    }
}
