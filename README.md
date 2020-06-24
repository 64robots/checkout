<p align="center">
<a href="https://travis-ci.org/64robots/checkout"><img src="https://travis-ci.org/64robots/checkout.svg?branch=master" alt="Build Status"></a>
<a href="https://en.wikipedia.org/wiki/MIT_License"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT License"></a>
</p>

## Checkout

This package provides API endpoints and common functionality for cart, checkout, orders and coupons. You can use it with your own UI or use [Checkout Vue](https://github.com/64robots/checkout-vue) package that works with this API out of the box.

### Installation

You can install this package via composer:
```
composer require 64robots/checkout
```
Once installed, this package will automatically register its service provider.

You can publish the package migrations with:
```
php artisan vendor:publish --provider="R64\Checkout\CheckoutServiceProvider" --tag="migrations"
```

After the migrations have been published, you can create package tables with:
```
php artisan migrate
```
By running migrations, these tables will be created:
- `customers`
- `products`
- `cart`
- `cart_items`
- `orders`
- `order_items`
- `coupons`
- `order_purchases` 

You can also publish the package config with:
```
php artisan vendor:publish --provider="R64\Checkout\CheckoutServiceProvider" --tag="config"
```

After the config has been published, you can find it's contents in `config/checkout.php`

```php
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
        'percentage_fee' => env('CHECKOUT_STRIPE_PERCENTAGE_FEE', 29 / 1000),
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
    'payment' => R64\Checkout\StripePaymentHandler::class
];
```

### Nova

You can publish nova resources with:

```
php artisan vendor:publish --provider="R64\Checkout\CheckoutServiceProvider" --tag="nova"
```

### Available API Endpoints

Once you install the package and run migrations, these API endpoints will be available in your application.

[API Docs](https://github.com/64robots/checkout/wiki/API-Endpoints)

## Licence

Checkout is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT)
