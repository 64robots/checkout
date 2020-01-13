<?php

namespace R64\Checkout;

class CheckoutFields
{
    private static $fields = [
        'shipping_first_name',
        'shipping_last_name',
        'customer_email',
        'shipping_address_line1',
        'shipping_address_line2',
        'shipping_address_city',
        'shipping_address_region',
        'shipping_address_region',
        'shipping_address_zipcode',
        'shipping_address_phone',
        'billing_address_line1',
        'billing_address_line2',
        'billing_address_city',
        'billing_address_region',
        'billing_address_region',
        'billing_address_zipcode',
        'billing_address_phone'
    ];

    public static function required()
    {
        $availableFields = array_fill_keys(static::$fields, false);
        $configFields = array_fill_keys(config('checkout.required', []), true);
        $configFields = collect($configFields)->intersectByKeys($availableFields)->all();

        return collect($availableFields)->merge($configFields)->all();
    }
}
