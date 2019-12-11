<?php
$HTTP_HOST = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';

return [
    'client_side_timestamp' => 201809091946, // Timestamp to prevent CSS/JS caching
    'google_analytics_tracking_code' => env('GOOGLE_ANALYTICS'),
    'http_host' => $HTTP_HOST,
    'premium_name' => env('APP_NAME'),
    'legal_name' => env('APP_NAME'),
    'mail_address_from' => env('MAIL_FROM_ADDRESS'),
    'mail_name_from' => env('MAIL_FROM_NAME'),
    'available_languages' => [
      'en' => 'English'
    ],
    'languages_default_currency' => [
      'en' => 'USD'
    ],
    'default_language' => 'en'
];