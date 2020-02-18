<?php

namespace R64\Checkout\Tests;

use Axlon\PostalCodeValidation\ValidationServiceProvider;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use R64\Checkout\CheckoutServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            CheckoutServiceProvider::class,
            ValidationServiceProvider::class
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
    }


    protected function responseToData(TestResponse $response)
    {
        return json_decode($response->getContent(), true)['data'];
    }
}
