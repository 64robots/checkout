<?php

namespace R64\Checkout\Helpers\Address;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GeoNames
{
    /** @var Client */
    private $client;

    /** @var string */
    private $username;

    /** @var string */
    private $countryCode;

    /**
     * @param Client $client
     * @param string $username
     * @param string $countryCode
     */
    public function __construct(Client $client, $username, $countryCode)
    {
        $this->client = $client;
        $this->username = $username;
        $this->countryCode = $countryCode;
    }

    public function postalCodeLookup($postalCode)
    {
        $url = sprintf(
            "http://api.geonames.org/postalCodeLookupJSON?postalcode=%s&country=%s&username=%s",
            $postalCode,
            $this->countryCode,
            $this->username
        );

        try {
            $response = $this->client->get($url);
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        $response = json_decode($response->getBody(), true);

        if (isset($response['status'])) {
            throw new \RuntimeException($response['status']['message']);
        }

        if (isset($response['postalcodes'])) {
            return $response['postalcodes'];
        }

        throw new \RuntimeException(var_export($response, true));
    }
}
