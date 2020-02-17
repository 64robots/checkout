<?php

namespace R64\Checkout\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use R64\Checkout\Helpers\Address\Address;
use R64\Checkout\Helpers\Address\AddressSearch;
use R64\Checkout\Helpers\Address\FakeGeoNames;

class AddressSearchTest extends TestCase
{
    /**
     * @test
     */
    public function asds()
    {
        $search = new AddressSearch(
            new FakeGeoNames(new Client(), 'username', 'code')
        );

        /** @var Address $address */
        $address = $search->getByPostalCode(12345)->first();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Baltimore', $address->getCityName());
        $this->assertEquals('MD', $address->getStateCode());
        $this->assertEquals('Maryland', $address->getStateName());
        $this->assertEquals(-76.562940, $address->getLongitude());
        $this->assertEquals(39.293810, $address->getLatitude());
    }
}
