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
    protected $signature = 'heartbeat
        {--queue : Dispatch the signal as a queued job}
        {--on-queue= : The queue on which the job is dispatched (implies --queue)}
        {--on-connection= : The connection on which the job is dispatched (implies --queue)}
        {channel}
        {options*}';

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

        $queue = $this->option('on-queue');
        $connection = $this->option('on-connection');

        if ($this->option('queue') || $queue || $connection) {
            $job = new HeartbeatJob($channel, ...$options);

            if ($queue) {
                $job->onQueue($queue);
            }

            if ($connection) {
                $job->onConnection($connection);
            }

            dispatch($job);
            return;
        }

        Heartbeat::channel($channel)->signal(...$options);
    }
}
