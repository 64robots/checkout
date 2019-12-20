<?php

if (!function_exists('getProperty')) {
    function getProperty($obj, $property, $failedReturn = null)
    {
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }
        return $failedReturn;
    }
}

if (!function_exists('jsonEncodeDecode')) {
    function jsonEncodeDecode($data)
    {
        return json_decode(json_encode($data));
    }
}

if (!function_exists('getPercent')) {
    function getPercent(int $value)
    {
        return round($value * 100, 1);
    }
}

if (!function_exists('displayMoney')) {
    function displayMoney(int $value)
    {
        return number_format($value / 100, 2);
    }
}

if (!function_exists('displayTaxRate')) {
    function displayTaxRate($value)
    {
        return number_format(!is_null($value) ? $value / 100 : 0, 2);
    }
}

if (!function_exists('subdomainUrl')) {
    function subdomainUrl(string $subdomain)
    {
        $http = config('app.env') == 'local' ? 'http://' : 'https://';
        return $http . $subdomain . '.' . env('APP_BASE_URL');
    }
}

/**
 * Strip out and lowercase hashtags
 */
if (!function_exists('normalize_tag')) {
    function normalize_tag($tag)
    {
        $normalized = ltrim($tag, '#');
        $normalized = mb_strtolower($normalized);

        return $normalized;
    }
}

/**
 * Check if user authenticated and he's an admin.
 */
if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return (bool) optional(auth()->user())->is_admin;
    }
}

/**
 * Check if user is not admin.
 */
if (!function_exists('isNotAdmin')) {
    function isNotAdmin()
    {
        return !isAdmin();
    }
}

/**
 * Generate an unique key based on given keys.
 * Keys can be passed both as an array or as arguments.
 */
if (!function_exists('generateUniqueKey')) {
    function generateUniqueKey(): string
    {
        // Transform key to an array of keys if it's not an array yet.
        $keys = is_array(func_get_args()[0]) ? func_get_args()[0] : func_get_args();

        return 'unq_' . implode('_', $keys);
    }
}
