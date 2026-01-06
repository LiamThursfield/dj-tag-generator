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

    /*
    |--------------------------------------------------------------------------
    | Text-to-Speech Services
    |--------------------------------------------------------------------------
    */

    'tts' => [
        'default' => env('TTS_SERVICE', 'openai'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_TTS_MODEL', 'tts-1'),
        'voice' => env('OPENAI_TTS_VOICE', 'alloy'),
    ],

    'elevenlabs' => [
        'api_key' => env('ELEVENLABS_API_KEY'),
        'model' => env('ELEVENLABS_MODEL', 'eleven_multilingual_v2'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Services
    |--------------------------------------------------------------------------
    */

    'minio' => [
        'endpoint' => env('MINIO_ENDPOINT'),
        'access_key_id' => env('MINIO_ACCESS_KEY_ID'),
        'secret_access_key' => env('MINIO_SECRET_ACCESS_KEY'),
        'bucket' => env('MINIO_BUCKET'),
        'use_path_style_endpoint' => env('MINIO_USE_PATH_STYLE_ENDPOINT', true),
    ],

    'r2' => [
        'access_key_id' => env('R2_ACCESS_KEY_ID'),
        'secret_access_key' => env('R2_SECRET_ACCESS_KEY'),
        'bucket' => env('R2_BUCKET'),
        'endpoint' => env('R2_ENDPOINT'),
    ],
];
