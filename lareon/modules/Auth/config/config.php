<?php

return [
    'name' => 'Auth',

    "oauth" => [
        'enabled' => true,
        'types'   => [
            'google'   => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/google/callback',
            ],
            'Linkedin' => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/Linkedin/callback',
            ],

            'github'   => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/github/callback',
            ],
            'gitlab'   => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/gitlab/callback',
            ],
            'facebook' => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/facebook/callback',
            ],
            'twitter'  => [
                'secret_key' => env('GOOGLE_SECRET_KEY'),
                'client_id'  => env('GOOGLE_GOOGLE_CLIENT_ID'),
                'redirect'   => '/oauth/twitter/callback',
            ],
        ],
    ],
];
