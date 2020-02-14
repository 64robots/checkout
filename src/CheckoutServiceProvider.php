<?php

namespace R64\Checkout;

use GuzzleHttp\Client;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use R64\Checkout\Contracts\Cart;
use R64\Checkout\Contracts\CartItem;
use R64\Checkout\Contracts\Coupon;
use R64\Checkout\Contracts\Customer;
use R64\Checkout\Contracts\Order;
use R64\Checkout\Contracts\OrderItem;
use R64\Checkout\Contracts\Product;
use R64\Checkout\Contracts\State;
use R64\Checkout\Helpers\Address\GeoNames;
use R64\Checkout\Contracts\PaymentHandler;
use R64\Stripe\PaymentProcessor;

class CheckoutServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/checkout.php', 'checkout'
        );

        $this->app->bind(Product::class, function () {
            $productClass = config('checkout.product_model');

            return new ConfigurableModel(new $productClass);
        });

        $this->app->bind(Customer::class, function () {
            $customerClass = config('checkout.customer_model');

            return new ConfigurableModel(new $customerClass);
        });

        $this->app->bind(Cart::class, function () {
            $cartClass = config('checkout.cart_model');

            return new ConfigurableModel(new $cartClass);
        });

        $this->app->bind(CartItem::class, function () {
            $cartItemClass = config('checkout.cart_item_model');

            return new ConfigurableModel(new $cartItemClass);
        });

        $this->app->bind(Order::class, function () {
            $orderClass = config('checkout.order_model');

            return new ConfigurableModel(new $orderClass);
        });

        $this->app->bind(OrderItem::class, function () {
            $orderItemClass = config('checkout.order_item_model');

            return new ConfigurableModel(new $orderItemClass);
        });

        $this->app->bind(Coupon::class, function () {
            $couponClass = config('checkout.coupon_model');

            return new ConfigurableModel(new $couponClass);
        });

        $this->app->singleton(State::class, function () {
            return new \R64\Checkout\State();
        });

        $this->app->singleton(PaymentHandler::class, function (Container $app) {
            $paymentHandler = config('checkout.payment');

            return new $paymentHandler($app->get(PaymentProcessor::class));
        });

        $this->app->singleton(GeoNames::class, function () {
            return new GeoNames(
                new Client(),
                config('checkout.geo_names.username'),
                config('checkout.geo_names.country_code')
            );
        });
    }

    public function boot(Filesystem $filesystem)
    {
        require_once __DIR__ . '/Helpers/Globals.php';
        $this->publishConfig();
        $this->publishPolicies();
        $this->publishDatabaseMigrations($filesystem);
        $this->publishNovaResources();
        $this->registerRoutes();
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/' => base_path('config'),
        ], 'config');
    }

    protected function publishDatabaseMigrations(Filesystem $filesystem)
    {
        $time = time();

        if (!class_exists('CreateCheckoutProductsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_products_table', $time, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/001_create_products_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCustomersTable')) {
            $migrationFileName = $this->getMigrationFilename('create_customers_table', $time + 1, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/002_create_customers_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCouponsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_coupons_table', $time + 2, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/003_create_coupons_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCartsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_carts_table', $time + 3, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/004_create_carts_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateCartItemsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_cart_items_table', $time + 4, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/005_create_cart_items_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateOrdersTable')) {
            $migrationFileName = $this->getMigrationFilename('create_orders_table', $time + 5, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/006_create_orders_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateOrderItemsTable')) {
            $migrationFileName = $this->getMigrationFilename('create_order_items_table', $time + 6, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/007_create_order_items_table.php' => $migrationFileName,
            ], 'migrations');
        }

        if (!class_exists('CreateOrderPurchasesTable')) {
            $migrationFileName = $this->getMigrationFilename('create_order_purchases_table', $time + 7, $filesystem);
            $this->publishes([
                __DIR__ . '/../database/migrations/008_create_order_purchases_table.php' => $migrationFileName,
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../database/factories/' => database_path('factories'),
        ], 'migrations');

    }

    protected function publishPolicies()
    {
        $this->publishes([
            __DIR__ . '/../Policies/' => app_path('Policies'),
        ], 'policies');
    }

    protected function publishNovaResources()
    {
        $this->publishes([
            __DIR__ . '/../Nova/' => app_path('Nova'),
        ], 'nova');
    }

    protected function registerRoutes()
    {
        Route::group($this->guestRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api_guest.php');
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

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($migrationName, $filesystem) {
                return $filesystem->glob($path . '*_' . $migrationName . '.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationName}.php")
            ->first();
    }
}
