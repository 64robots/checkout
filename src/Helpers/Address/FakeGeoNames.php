<?php

namespace R64\Checkout\Helpers\Address;

class FakeGeoNames extends GeoNames
{
    public function postalCodeLookup($postalCode)
    {
        return [
            [
                'placeName' => 'Baltimore',
                'adminName1' => 'Maryland',
                'adminCode1' => 'MD',
                'lat' => 39.293810,
                'lng' => -76.562940
            ]
        ];
    }
}
