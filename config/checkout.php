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
    'toc_url' => '#',
    'stripe' => [
        'percentage_fee' => env('STRIPE_PERCENTAGE_FEE', 29 / 1000),
        'fixed_fee' => env('STRIPE_FIXED_FEE', 30)
    ],
    'geo_names' => [
        'username' => env('GEO_NAMES_USERNAME', 'demo'),
        'country_code' => env('GEO_NAMES_COUNTRY', 'US')
    ],
    'product_model' => R64\Checkout\Models\Product::class,
    'customer_model' => R64\Checkout\Models\Customer::class,
    'cart_model' => R64\Checkout\Models\Cart::class,
    'payment' => R64\Checkout\PaymentHandler::class
];
