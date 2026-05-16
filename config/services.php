<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google_ads' => [
        'api_token' => env('GOOGLE_ADS_API_TOKEN'),
        'customer_id' => env('GOOGLE_ADS_CUSTOMER_ID'),
        'login_customer_id' => env('GOOGLE_ADS_LOGIN_CUSTOMER_ID'),
        'developer_token' => env('GOOGLE_ADS_DEVELOPER_TOKEN'),
        'client_id' => env('GOOGLE_ADS_CLIENT_ID'),
        'client_secret' => env('GOOGLE_ADS_CLIENT_SECRET'),
        'refresh_token' => env('GOOGLE_ADS_REFRESH_TOKEN'),
        'conversion_id' => env('GOOGLE_ADS_CONVERSION_ID'),
        'labels' => [
            'lead' => env('GOOGLE_ADS_LEAD_LABEL'),
            'phone' => env('GOOGLE_ADS_PHONE_LABEL'),
            'whatsapp' => env('GOOGLE_ADS_WHATSAPP_LABEL'),
            'quote' => env('GOOGLE_ADS_QUOTE_LABEL'),
        ],
    ],

    'ga4' => [
        'measurement_id' => env('GA4_MEASUREMENT_ID'),
    ],

    'meta_ads' => [
        'access_token' => env('META_ADS_ACCESS_TOKEN'),
        'ad_account_id' => env('META_ADS_ACCOUNT_ID'),
    ],

];
