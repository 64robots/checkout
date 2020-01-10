<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\\R64\\Checkout\\Http\\Controllers')->group(function () {
    /***************************************************************************************
     ** Cart
     ***************************************************************************************/

    Route::get('carts/{cart}', 'CartController@get');
    Route::get('carts/{cart}/total', 'CartTotalController@get');
    Route::post('carts', 'CartController@create');
    Route::put('carts/{cart}', 'CartController@update');
    Route::delete('carts/{cart}', 'CartController@delete');

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
});

