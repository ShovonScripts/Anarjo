<?php

return [
    'bkash' => [
        'app_key' => env('BKASH_APP_KEY', ''),
        'app_secret' => env('BKASH_APP_SECRET', ''),
        'username' => env('BKASH_USERNAME', ''),
        'password' => env('BKASH_PASSWORD', ''),
    ],

    'sslcommerz' => [
        'store_id' => env('SSLCOMMERZ_STORE_ID', ''),
        'store_passwd' => env('SSLCOMMERZ_STORE_PASSWD', ''),
    ],
];
