<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::namespace('\\R64\\Checkout\\Http\\Controllers')->group(function () {
    /***************************************************************************************
     ** Cart
     ***************************************************************************************/

    Route::get('carts/{cart}', 'CartController@get');
    Route::post('carts', 'CartController@create');
    Route::put('carts/{cart}', 'CartController@update');
    Route::delete('carts/{cart}', 'CartController@delete');

    Route::put('carts/{cart}/zipcode', 'CartZipCodeController@update');
    Route::put('carts/{cart}/shipping', 'CartShippingController@update');
    Route::put('carts/{cart}/email', 'CartEmailController@update');
    Route::post('carts/{cart}/options', 'CartOptionsController@store');

    Route::put('carts/{cart}/coupon-code', 'CartCouponController@update');
    Route::delete('carts/{cart}/coupon-code', 'CartCouponController@delete');

    /***************************************************************************************
     ** Cart Items
     ***************************************************************************************/

    Route::post('carts/{cart}/cart-items', 'CartItemController@create');
    Route::put('cart-items/{cartItem}', 'CartItemController@update');
    Route::delete('cart-items/{cartItem}', 'CartItemController@delete');

    /***************************************************************************************
     ** Checkout
     ***************************************************************************************/

    Route::get('checkout/settings', 'CheckoutSettingsController@index');

    /***************************************************************************************
     ** Orders
     ***************************************************************************************/
    Route::get('orders/{order}', 'OrderController@get');
    Route::post('orders', 'OrderController@create');
});

