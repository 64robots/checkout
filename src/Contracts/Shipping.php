<?php

namespace R64\Checkout\Contracts;

interface Shipping
{
    public function getShippingMethods();

    public function find($id);
}
