<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Heartbeat schedule
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure a heartbeat that will be signal
    | periodically according to the cron expression specified. This heartbeat
    | is trigger by Laravel's scheduler and is run asynchronously to validate
    | that the queue system is still working.
    |
    */

    'schedule' => [
        'preset' => env('HEARTBEAT_SCHEDULE_PRESET'),

        'cron' => env('HEARTBEAT_SCHEDULE_CRON', '*/15 * * * *'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Heartbeat presets
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the heartbeats presets that will be used to
    | send signals. Samples of each available type of connection are provided
    | inside this array.
    |
    */

    'presets' => [
        'file' => [
            'channel' => 'file',
            'file' => '/tmp/file-heartbeat',
        ],

        'http' => [
            'channel' => 'http',
            'url' => 'https://beats.envoyer.io/heartbeat/example',
            'options' => [
                //
            ],
        ],
    ],
];
