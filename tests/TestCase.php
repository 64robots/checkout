<?php

namespace R64\Checkout\Tests;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use R64\Checkout\CheckoutServiceProvider;
use R64\Stripe\StripeServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'R64\\Checkout\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            CheckoutServiceProvider::class,
            ValidationServiceProvider::class,
            StripeServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('stripe.mock', true);
    }


    protected function responseToData(TestResponse $response)
    {
        return json_decode($response->getContent(), true)['data'];
    }
}
