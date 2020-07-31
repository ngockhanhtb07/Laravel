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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Model\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'search' => [
        'enabled' => env('ELASTIC_SEARCH_ENABLED', false)
    ],

    'shipping' => [
        'ghn' => [
            'enabled' => env('SHIPPING_GHN_ENABLED', true),
            'environment' => [
                'local' => env('SHIPPING_GHN_ENV_URL_LOCAL'),
                'staging' => env('SHIPPING_GHN_ENV_URL_STAGING'),
                'production' => env('SHIPPING_GHN_ENV_URL_PRODUCTION')
            ],
            'account' => [
                'email' => env('SHIPPING_GHN_ENV_ACCOUNT_EMAIL'),
                'password' => env('SHIPPING_GHN_ENV_ACCOUNT_PASSWORD'),
                'token' => env('SHIPPING_GHN_ENV_ACCOUNT_TOKEN')
            ],
            'api' => [
                'token' => 'SignIn',
                'districts' => 'GetDistricts',
                'calculateFee' => 'CalculateFee',
                'shipment' => [
                    'create' => 'CreateOrder',
                    'cancel' => 'CancelOrder'
                ]
            ]


        ],

    ],
    'medias' => [
        'base_uri' => env('MEDIA_SERVICE_URL'),
        'secret' =>env('MEDIA_SERVICE_SECRET')
    ],
    'gateway' => [
        'base_uri' => env('GATEWAY_SERVICE_URL'),
        'secret'   => env('GATEWAY_SERVICE_SECRET')
    ]

];
