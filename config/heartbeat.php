<?php

return [

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
            'file' => '/tmp/file.heartbeat',
        ],

        'http' => [
            'channel' => 'http',
            'url' => 'https://beats.envoyer.io/heartbeat/example',
            'options' => [
                //
            ],
        ],

        'disk' => [
            'channel' => 'disk',
            'disk' => 'local',
            'file' => 'disk.heartbeat',
        ],

        /*
        |--------------------------------------------------------------------------
        | Queue preset
        |--------------------------------------------------------------------------
        |
        | Here is a default configuration that could be used to monitor your queue system.
        | See configuration option `job_schedule` below.
        |
        */

        'queue' => [
            'channel' => 'disk',
            'disk' => 'local',
            'file' => 'queue.heartbeat',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Heartbeat job schedule
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure a heartbeat that will be signal
    | periodically according to the cron expression specified. This heartbeat
    | is trigger by Laravel's scheduler and is run asynchronously to validate
    | that the queue system is still working.
    |
    */

    'job_schedule' => [
        'preset' => env('HEARTBEAT_JOB_SCHEDULE_PRESET', 'queue'),

        'cron' => env('HEARTBEAT_JOB_SCHEDULE_CRON', '*/15 * * * *'),
    ],
];
