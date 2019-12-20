<?php

namespace R64\Checkout;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CheckoutServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishConfig();
        $this->publishPolicies();
        $this->publishDatabaseMigrations();
        $this->publishNovaResources();
        $this->registerRoutes();
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/' => base_path('config'),
        ], 'config');
    }

    protected function publishDatabaseMigrations()
    {
        $time = time();

        if (!class_exists('CreateCheckoutProductsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/001_create_checkout_products_table.php' => database_path('migrations/' . date('Y_m_d_His', $time) . '_create_checkout_products_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateCartsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/002_create_carts_table.php' => database_path('migrations/' . date('Y_m_d_His', $time + 1) . '_create_carts_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateCartItemsTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/003_create_cart_items_table.php' => database_path('migrations/' . date('Y_m_d_His', $time + 2) . '_create_cart_items_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateOrdersTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/004_create_orders_table.php' => database_path('migrations/' . date('Y_m_d_His', $time + 3) . '_create_orders_table.php'),
            ], 'migrations');
        }

        if (!class_exists('CreateOrderItemsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/005_create_order_items_table.php' => database_path('migrations/' . date('Y_m_d_His', $time + 4) . '_create_order_items_table.php'),
            ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../database/factories/' => database_path('factories'),
        ], 'migrations');

    }

    protected function publishPolicies()
    {
        $this->publishes([
            __DIR__.'/../Policies/' => app_path('Policies'),
        ], 'policies');
    }

    protected function publishNovaResources()
    {
        $this->publishes([
            __DIR__.'/../Nova/' => app_path('Nova'),
        ], 'nova');
    }

    protected function registerRoutes()
    {
        Route::group($this->guestRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api_guest.php');
        });

        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function guestRouteConfiguration()
    {
        return [
            'namespace' => 'R64\Checkout\Http\Controllers',
            'as' => 'checkout.api.',
            'prefix' => 'api',
            'middleware' => 'api-guest',
        ];
    }

    protected function apiRouteConfiguration()
    {
        return [
            'namespace' => 'R64\Checkout\Http\Controllers',
            'as' => 'checkout.api.',
            'prefix' => 'api',
            'middleware' => 'api',
        ];
    }
}
