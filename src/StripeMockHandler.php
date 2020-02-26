<?php

namespace R64\Checkout;

use Faker\Factory;
use R64\Stripe\MockHandler;
use R64\Stripe\Objects\Charge;
use Stripe\Charge as StripeCharge;
use Mockery as m;

class StripeMockHandler extends MockHandler
{
    /*********************************************************************************/

    /** CHARGE
     **********************************************************************************/
    public function createCharge(array $params)
    {
        $stripeCharge = $this->getMockStripeCharge($params);
        $charge = $stripeCharge::create($params, $this->stripeConnectParam());

        m::close();

        if ($charge) {
            return new Charge($charge);
        }
    }

    private function getMockStripeCharge($params)
    {
        $charge = m::mock('alias:StripeCharge');

        $charge
            ->shouldReceive('create')
            ->with([
                'customer' => $params['customer'],
                'amount' => $params['amount'],
                'currency' => $params['currency']
            ], $this->stripeConnectParam())
            ->andReturn($this->getStripeCharge($params));

        $this->successful = true;

        return $charge;
    }

    private function getStripeCharge($params)
    {
        $faker = Factory::create();

        $charge = new StripeCharge(['id' => 'ch_1']);
        $charge->amount = $params['amount'];
        $charge->currency = $params['currency'];
        $charge->created = time();
        $charge->source = (object) [
            'id' => 'card_1',
            'object' => 'card',
            'name' => $faker->name,
            'brand' => $faker->creditCardType,
            'last4' => '4242',
            'exp_month' => $faker->numberBetween(1, 12),
            'exp_year' => now()->addYear()->year,
            'country' => 'US',
        ];

        return $charge;
    }
}
