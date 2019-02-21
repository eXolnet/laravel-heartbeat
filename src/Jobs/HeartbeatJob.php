<?php

namespace Exolnet\Heartbeat\Jobs;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HeartbeatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var array
     */
    public $options;

    /**
     * @param string $channel
     * @param array ...$options
     */
    public function __construct($channel, ...$options)
    {
        $this->channel = $channel;
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function handle()
    {
        Heartbeat::channel($this->channel)->signal(...$this->options);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['heartbeat', 'monitoring'];
    }
}
