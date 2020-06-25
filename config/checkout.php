<?php

return [

    /*
     * Required parameters when submitting an order to /api/orders endpoint
     */
    'required' => [
        'customer_email',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address_line1',
        // 'shipping_address_line2',
        'shipping_address_city',
        'shipping_address_region',
        'shipping_address_zipcode',
        'shipping_address_phone',
        'billing_first_name',
        'billing_last_name',
        'billing_address_line1',
         // 'billing_address_line2',
        'billing_address_city',
        'billing_address_region',
        'billing_address_zipcode',
        'billing_address_phone',
    ],

    /*
     * Terms and conditions url used usually in the checkout UI
     */
    'toc_url' => '#',

    /*
     * Currency code that will be saved with every order and symbol
     * that is usually used in the Cart, Checkout and Order UI
     */
    'currency' => [
        'code' => env('CHECKOUT_CURRENCY_CODE', 'USD'),
        'symbol' => env('CHECKOUT_CURRENCY_SYMBOL', '$')
    ],

    /*
     * Percentage of Cart total and fixed fee will be stored for every
     * order purchase (transaction)
     */
    'stripe' => [
        'percentage_fee' => env('CHECKOUT_STRIPE_PERCENTAGE_FEE', 2.9 / 100),
        'fixed_fee' => env('CHECKOUT_STRIPE_FIXED_FEE', 30)
    ],

    /*
     * Shipping city and state is automatically resolved from zip code
     * using GeoNames service http://www.geonames.org/
     *
     * Country code constraints the search results
     * to specific country
     */
    'geo_names' => [
        'username' => env('CHECKOUT_GEO_NAMES_USERNAME', 'demo'),
        'country_code' => env('CHECKOUT_GEO_NAMES_COUNTRY_CODE', 'US')
    ],

    /*
     * Class names can be replaced and extended with your own logic
     */
    'product_model' => R64\Checkout\Models\Product::class,
    'customer_model' => R64\Checkout\Models\Customer::class,
    'cart_model' => R64\Checkout\Models\Cart::class,
    'cart_item_model' => R64\Checkout\Models\CartItem::class,
    'coupon_model' => R64\Checkout\Models\Coupon::class,
    'order_model' => R64\Checkout\Models\Order::class,
    'order_item_model' => R64\Checkout\Models\OrderItem::class,
    'product_resource' => R64\Checkout\Http\Resources\ProductResource::class,
    'stripe_payment' => R64\Checkout\StripePaymentHandler::class,
    'paypal_payment' => R64\Checkout\PaypalPaymentHandler::class
];
