<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flutterwave variables
    |--------------------------------------------------------------------------
    |
    | The variables for Flutterwave from the .env file
    |
    */

    /**
     * Public Key: Your Rave publicKey. Sign up on https://rave.flutterwave.com/ to get one from your settings page
     *
     */
    'public_key' => env('FLUTTERWAVE_PUBLIC_KEY', ''),

    /**
     * Secret Key: Your Rave secretKey. Sign up on https://rave.flutterwave.com/ to get one from your settings page
     *
     */
    'secret_key' => env('FLUTTERWAVE_SECRET_KEY', ''),

    /**
     * Environment: This can either be 'staging' or 'live'
     *
     */
    'env' => env('FLUTTERWAVE_ENV', 'staging'),

    /**
     * Prefix: This is added to the front of your transaction reference numbers
     *
     */
    'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY', ''),
];
