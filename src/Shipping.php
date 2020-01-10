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
                'title' => '5 business days',
                'sub_title' => 'Get it ' . (new \DateTime('+5 days'))->format('l M d'),
                'price' => 0
            ],
            [
                'id' => 2,
                'title' => '2 business days',
                'sub_title' => 'Get it ' . (new \DateTime('+2 days'))->format('l M d'),
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
