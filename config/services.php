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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

'paymongo' => [
    'secret_key'     => env('PAYMONGO_SECRET_KEY'),
    'public_key'     => env('PAYMONGO_PUBLIC_KEY'),
    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),
],

'brevo' => [
    'api_key'   => env('BREVO_API_KEY'),
    'from_email'=> env('MAIL_FROM_ADDRESS', 'noreply@bahaytek.com'),
    'from_name' => env('MAIL_FROM_NAME', 'BAHAYTEK'),
],

'semaphore' => [
    'api_key'     => env('SEMAPHORE_API_KEY'),
    'sender_name' => env('SEMAPHORE_SENDER_NAME', 'BAHAYTEK'),
],

'cloudinary' => [
    'cloud_name'    => env('CLOUDINARY_CLOUD_NAME'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
],

];
