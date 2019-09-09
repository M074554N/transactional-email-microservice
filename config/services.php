<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
    'mail_service_providers' => env('MAIL_SERVICE_PROVIDERS'),

    'default_mail_provider' => env('DEFAULT_MAIL_PROVIDER'),

    'sendgrid' => [
        'api_key'   => env('SENDGRID_API_KEY'),
        'endpoint'  => env('SENDGRID_ENDPOINT'),
    ],
    
    'mailjet' => [
        'api_key'   => env('MAILJET_API_KEY'),
        'secret'    => env('MAILJET_API_SECRET'),
    ],

    'mailgun' => [
        'api_key'    => env('MAILGUN_API_KEY'),
    ],

    'postmark' => [
        'token'     => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'       => env('AWS_ACCESS_KEY_ID'),
        'secret'    => env('AWS_SECRET_ACCESS_KEY'),
        'region'    => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret'    => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model'     => App\User::class,
        'key'       => env('STRIPE_KEY'),
        'secret'    => env('STRIPE_SECRET'),
        'webhook'   => [
            'secret'    => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

];
