<?php

namespace R64\Checkout\Helpers\Address;

use RuntimeException;

class AddressSearch
{
    /** @var GeoNames */
    private $geoNames;

    /**
     * @param GeoNames $geoNames
     */
    public function __construct(GeoNames $geoNames)
    {
        $this->geoNames = $geoNames;
    }

    public function getByPostalCode($postalCode)
    {
        try {
            return collect($this->geoNames->postalCodeLookup($postalCode))
                ->map(function ($address) {
                    return (new Address())
                        ->setCityName($address['placeName'])
                        ->setStateName($address['adminName1'])
                        ->setStateCode($address['adminCode1'])
                        ->setLatitude($address['lat'])
                        ->setLongitude($address['lng']);

                });
        } catch (RuntimeException $e) {
            //
        }

        return collect();
    }
}
