<?php

namespace R64\Checkout\Tests\Controllers;

use R64\Checkout\Tests\TestCase;

class CheckoutSettingsControllerTest extends TestCase
{
    /**
     * @test
     * GET /api/checkout/settings
     */
    public function anyone_can_get_checkout_settings()
    {
        $this
            ->json('GET', '/api/checkout/settings')
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'required',
                    'states',
                    'toc_url',
                    'currency_symbol'
                ]
            ]);
    }
}
