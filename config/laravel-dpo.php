<?php

return [
    'company_token' => env('DPO_COMPANY_TOKEN', 'B3F59BE7-0756-420E-BB88-1D98E7A6B040'),
    'service_type' => env('DPO_SERVICE_TYPE', '54841'),
    'service_description' => env('DPO_SERVICE_DESCRIPTION', 'TEMPO AFRICA SERVICE BOOKING'),
    'back_url' => env('DPO_BACK_URL'),
    'redirect_url' => env('DPO_REDIRECT_URL'),
    'live_mode' => env('DPO_LIVE_MODE', true),
    'default_currency' => env('DPO_DEFAULT_CURRENCY'),
    'default_country' => env('DPO_DEFAULT_COUNTRY'),
    'callback_url' => env('DPO_CALLBACK_URL'),
    'live_url' => env('DPO_LIVE_URL'),
    'test_url' => env('DPO_TEST_URL'),
];
