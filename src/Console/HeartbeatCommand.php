<?php

namespace Exolnet\Heartbeat\Console;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Exolnet\Heartbeat\Jobs\HeartbeatJob;
use Illuminate\Console\Command;

class HeartbeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heartbeat {--queue} {channel} {options*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a heartbeat signal';

    /**
     * @return void
     */
    public function handle(): void
    {
        $channel = $this->argument('channel');
        $options = $this->argument('options');

        if ($this->option('queue')) {
            dispatch(new HeartbeatJob($channel, ...$options));
            return;
        }

        Heartbeat::channel($channel)->signal(...$options);
    }
}
