<?php

return [

    'name' => 'Donation',

    'payment' => [
        'sslcommerz' => [
            'store_id'       => env('SSLC_STORE_ID'),
            'store_password' => env('SSLC_STORE_PASSWORD'),
            'sandbox'        => env('SSLC_SANDBOX', true),
        ],
        'bkash' => [
            'base_url'  => env('BKASH_BASE_URL', 'https://checkout.sandbox.bka.sh'),
            'app_key'   => env('BKASH_APP_KEY'),
            'app_secret'=> env('BKASH_APP_SECRET'),
            'username'  => env('BKASH_USERNAME'),
            'password'  => env('BKASH_PASSWORD'),
        ],
        'nagad' => [
            'base_url'        => env('NAGAD_BASE_URL', 'https://sandbox.mynagad.com'),
            'merchant_id'     => env('NAGAD_MERCHANT_ID'),
            'merchant_number' => env('NAGAD_MERCHANT_NUMBER'),
            'secret_key'      => env('NAGAD_SECRET_KEY'),
            'callback_url'    => env('NAGAD_CALLBACK_URL', 'http://your-app.test/donation/nagad/callback'),
        ],
    ],

];