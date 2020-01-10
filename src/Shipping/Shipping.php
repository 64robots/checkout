<?php

namespace R64\Checkout\Shipping;

use R64\Checkout\Contracts\Shipping as ShippingContract;

class Shipping implements ShippingContract
{
    public function getShippingMethods()
    {
        return [
            [
                'name' => '5 business days',
                'price' => 0,
                'deliver_by' => (new \DateTime('+5 days'))->format('Y-m-d')
            ],
            [
                'name' => '2 business days',
                'price' => 650,
                'deliver_by' => (new \DateTime('+2 days'))->format('Y-m-d')
            ]
        ];
    }
}
