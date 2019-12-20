<?php

namespace R64\Checkout;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CheckoutServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $this->publishConfig();
        $this->publishPolicies();
        $this->publishDatabaseMigrations($filesystem);
        $this->publishNovaResources();
        $this->registerRoutes();
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/' => base_path('config'),
        ], 'config');
    }

    protected function publishDatabaseMigrations(Filesystem $filesystem)
    {
        $time = time();

        if (!class_exists('CreateCheckoutProductsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_checkout_products_table', $time, $filesystem);
            $this->publishes([
                __DIR__.'/../database/migrations/001_create_checkout_products_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCartsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_carts_table', $time + 1, $filesystem);
            $this->publishes([
                __DIR__.'/../database/migrations/002_create_carts_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCartItemsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_cart_items_table', $time + 2, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/003_create_cart_items_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateOrdersTable')) {
            $migrationFileName = $this->getMigrationFilename('create_orders_table', $time + 3, $filesystem);
            $this->publishes([
                __DIR__.'/../database/migrations/004_create_orders_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateOrderItemsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_order_items_table', $time + 4, $filesystem);
            $this->publishes([
                __DIR__.'/../database/migrations/005_create_order_items_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCouponsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_coupons_table', $time + 5, $filesystem);
            $this->publishes([
                __DIR__.'/../database/migrations/006_create_coupons_table.php' => $migrationFileName,
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

    protected function getMigrationFileName($migrationName, $time, Filesystem $filesystem)
    {
        $timestamp = date('Y_m_d_His', $time);
        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($migrationName, $filesystem) {
                return $filesystem->glob($path.'*_' . $migrationName . '.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationName}.php")
            ->first();
    }
}
