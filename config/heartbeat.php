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

        'newrelic' => [
           'channel' => 'newrelic',
           'account' => env('HEARTBEAT_NEWRELIC_ACCOUNT'),
           'key' => env('HEARTBEAT_NEWRELIC_KEY'),
           'eventType' => env('HEARTBEAT_NEWRELIC_EVENT_TYPE', 'HeartbeatEvent'),
           'eventData' => [
               //
           ]
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
    | The connection and queue on which the job is dispatched may be specified
    | (null uses the defaults). To schedule multiple heartbeat jobs, e.g. to
    | monitor several connections or queues, use a list of entries instead:
    |
    |   'job_schedule' => [
    |       ['preset' => 'disk', 'cron' => '0 * * * *'],
    |       ['preset' => 'http', 'cron' => '* * * * *', 'queue' => 'high'],
    |   ],
    |
    */

    'job_schedule' => [
        'preset' => env('HEARTBEAT_JOB_SCHEDULE_PRESET', 'disk'),

        'cron' => env('HEARTBEAT_JOB_SCHEDULE_CRON', '*/15 * * * *'),

        'connection' => env('HEARTBEAT_JOB_SCHEDULE_CONNECTION'),

        'queue' => env('HEARTBEAT_JOB_SCHEDULE_QUEUE'),
    ],
];
