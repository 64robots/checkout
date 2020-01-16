<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\Shipping as ShippingContract;

class Shipping implements ShippingContract
{
    public function getShippingMethods()
    {
        return [
            [
                'id' => 1,
                'delivery_days' => 5,
                'delivery_date' => (new \DateTime('+5 days')),
                'price' => 0
            ],
            [
                'id' => 2,
                'delivery_days' => 2,
                'delivery_date' => (new \DateTime('+2 days')),
                'price' => 650
            ]
        ];
    }

    public function find($id)
    {
        return collect($this->getShippingMethods())->filter(function ($method) use ($id) {
            return $method['id'] === (int) $id;
        })->first();
    }
}
