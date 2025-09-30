<?php

return [

    'bkash' => [
        'base_url' => env('BKASH_BASE_URL', 'https://checkout.sandbox.bka.sh/v1.2.0-beta'),
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
    ],

    'nagad' => [
        'merchant_id' => env('NAGAD_MERCHANT_ID'),
        'merchant_number' => env('NAGAD_MERCHANT_NUMBER'),
        'secret_key' => env('NAGAD_SECRET_KEY'),
        'callback_url' => env('NAGAD_CALLBACK_URL'),
        'base_url' => env('NAGAD_BASE_URL', 'https://sandbox.mynagad.com/'),
    ],

    'sslcommerz' => [
        'store_id' => env('SSLCOMMERZ_STORE_ID'),
        'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
        'is_live' => env('SSLCOMMERZ_LIVE', false),
    ],

];