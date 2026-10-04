<?php

return [
    'name' => 'Steward',

    'admin' => [
        'layout' => [
            'navbar' => 'simple',
        ],
    ],

    'relations_on_trash' => [
    ],

    'slow_query' => [
        /*
        |--------------------------------------------------------------------------
        | Enabled
        |--------------------------------------------------------------------------
        |
        | Enable or disable slow query monitoring.
        |
        */

        'enabled' => env('SLOW_QUERY_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Threshold
        |--------------------------------------------------------------------------
        |
        | Queries taking equal to or longer than this amount in milliseconds
        | will be logged.
        |
        */

        'threshold' => (int)env('SLOW_QUERY_THRESHOLD', 500),

        /*
        |--------------------------------------------------------------------------
        | Log Path
        |--------------------------------------------------------------------------
        |
        */

        'path' => storage_path('logs'),

        /*
        |--------------------------------------------------------------------------
        | Keep Months
        |--------------------------------------------------------------------------
        |
        | Number of monthly log files to keep.
        |
        */

        'keep_months' => (int)env('SLOW_QUERY_KEEP_MONTHS', 12),

        /*
        |--------------------------------------------------------------------------
        | Log Bindings
        |--------------------------------------------------------------------------
        |
        | Whether query bindings should be written to the log.
        |
        */

        'log_bindings' => env('SLOW_QUERY_LOG_BINDINGS', false),


    ],
];
