<?php

return [
    'appId' => env('INSTAGRAM_APP_ID'),
    'appSecret' => env('INSTAGRAM_APP_SECRET'),

    'limit' => env('INSTAGRAM_LIMIT', 12),

    'cache' => [
        'key_prefix' => 'instagram_',
        'duration' => 60 * 60 * 24, // 1 day
    ],

    'token' => [
        'storage_file' => 'nineteen/instagram/token.json',

        'days_before_expiration' => 30, // Refresh token 30 days before expiration
    ],

];
