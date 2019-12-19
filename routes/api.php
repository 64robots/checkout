<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\\R64\\Checkout\\Http\\Controllers')->group(function () {
    /***************************************************************************************
     ** Orders
     ***************************************************************************************/

    Route::get('my/orders', 'OrderController@list');
    Route::post('orders', 'OrderController@create');
    Route::delete('orders/{order}', 'OrderController@delete');
});
