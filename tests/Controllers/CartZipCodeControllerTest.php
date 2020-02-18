<?php

namespace R64\Checkout\Tests\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use R64\Checkout\Helpers\Address\FakeGeoNames;
use R64\Checkout\Helpers\Address\GeoNames;
use R64\Checkout\Models\Cart;
use R64\Checkout\Tests\TestCase;

class CartZipCodeControllerTest extends TestCase
{
    use RefreshDatabase, InteractsWithExceptionHandling;
    /**
     * @test
     * PUT /api/carts/{cart}
     */
    public function autofill_shipping_city_and_state_from_zipcode()
    {
        $this->withoutExceptionHandling();
        $this->instance(GeoNames::class, new FakeGeoNames(new Client(), 'username', 'code'));
        $cart = factory(Cart::class)->create();

        $response = $this->json('PUT', "/api/carts/{$cart->token}/zipcode", [
            'zipcode' => 21224,
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $response = json_decode($response->getContent(), true)['data'];

        $this->assertEquals($response['shipping_address_city'], 'Baltimore');
        $this->assertEquals($response['shipping_address_region'], 'Maryland');
    }
}
