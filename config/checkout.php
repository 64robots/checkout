<?php

return [
    'required' => [
//        'customer_email',
//        'shipping_first_name',
//        'shipping_last_name',
//        'shipping_address_line1',
//        'shipping_address_line2',
//        'shipping_address_city',
//        'shipping_address_region',
//        'shipping_address_region',
//        'shipping_address_zipcode',
//        'shipping_address_phone',
//        'billing_first_name',
//        'billing_last_name',
//        'billing_address_line1',
//        'billing_address_line2',
//        'billing_address_city',
//        'billing_address_region',
//        'billing_address_zipcode',
//        'billing_address_phone',
    ],

    'tax_rate' => 600,
    'toc_url' => '#',
    'stripe' => [
        'percentage_fee' => 29 / 1000,
        'fixed_fee' => 30
    ],
    'product_model' => R64\Checkout\Models\Product::class,
    'customer_model' => R64\Checkout\Models\Customer::class,
    'cart_model' => R64\Checkout\Models\Cart::class,
    'payment' => R64\Checkout\PaymentHandler::class,
    'order_estimate' => R64\Checkout\OrderEstimateHandler::class
];
