<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate URL
    |--------------------------------------------------------------------------
    |
    | The exchange rate url used to pull in the exchange rates
    |
    */

    'exchange_rate_url' => env('EXCHANGE_RATE_URL', 'https://api.exchangeratesapi.io/latest'),

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate Cache Expiry
    |--------------------------------------------------------------------------
    |
    | The amount of time in seconds before the Exchange API cache will expire. Defaults to 2 hours = 7200 seconds
    |
    */

    'exchange_cache_expiry' => env('EXCHANGE_CACHE_EXPIRY', 7200),



];