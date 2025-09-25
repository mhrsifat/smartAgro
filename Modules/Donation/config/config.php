<?php

// Donation Module Config

$sslApiDomain = env('SSLC_SANDBOX', true)
    ? "https://sandbox.sslcommerz.com"
    : "https://securepay.sslcommerz.com";

return [

    'name' => 'Donation',

    'payment' => [

        // ✅ Official SSLCommerz-style config
        'sslcommerz' => [
            'apiCredentials' => [
                'store_id'       => env('SSLC_STORE_ID'),
                'store_password' => env('SSLC_STORE_PASSWORD'),
            ],
            'apiUrl' => [
                'make_payment'       => "/gwprocess/v4/api.php",
                'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
                'order_validate'     => "/validator/api/validationserverAPI.php",
                'refund_payment'     => "/validator/api/merchantTransIDvalidationAPI.php",
                'refund_status'      => "/validator/api/merchantTransIDvalidationAPI.php",
            ],
            'apiDomain' => $sslApiDomain,
            'connect_from_localhost' => env("IS_LOCALHOST", false), // true = sandbox, false = live
            'success_url' => '/donation/ssl/success',
            'failed_url'  => '/donation/ssl/fail',
            'cancel_url'  => '/donation/ssl/cancel',
            'ipn_url'     => '/donation/ssl/ipn',
        ],

        // ✅ bKash config
        'bkash' => [
            'base_url'   => env('BKASH_BASE_URL', 'https://checkout.sandbox.bka.sh'),
            'app_key'    => env('BKASH_APP_KEY'),
            'app_secret' => env('BKASH_APP_SECRET'),
            'username'   => env('BKASH_USERNAME'),
            'password'   => env('BKASH_PASSWORD'),
        ],

        // ✅ Nagad config
        'nagad' => [
            'base_url'        => env('NAGAD_BASE_URL', 'https://sandbox.mynagad.com'),
            'merchant_id'     => env('NAGAD_MERCHANT_ID'),
            'merchant_number' => env('NAGAD_MERCHANT_NUMBER'),
            'secret_key'      => env('NAGAD_SECRET_KEY'),
            'callback_url'    => env('NAGAD_CALLBACK_URL', 'http://your-app.test/donation/nagad/callback'),
        ],
    ],

];
