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
            'file' => env('HEARTBEAT_FILE', '/tmp/file.heartbeat'),
        ],

        'http' => [
            'channel' => 'http',
            'url' => env('HEARTBEAT_URL', 'https://beats.envoyer.io/heartbeat/example'),
            'options' => [
                //
            ],
        ],

        'disk' => [
            'channel' => 'disk',
            'disk' => env('HEARTBEAT_DISK', env('FILESYSTEM_DISK', 'local')),
            'file' => env('HEARTBEAT_DISK_FILE', 'disk.heartbeat'),
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
        'preset' => env('HEARTBEAT_JOB_SCHEDULE_PRESET', 'disk'),

        'cron' => env('HEARTBEAT_JOB_SCHEDULE_CRON', '*/15 * * * *'),
    ],
];
