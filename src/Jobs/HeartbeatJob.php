<?php

namespace Exolnet\Heartbeat\Jobs;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HeartbeatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var int
     */
    public $tries = 3;

    /**
     * @var int
     */
    public $maxExceptions = 1;

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
    public function __construct(string $channel, ...$options)
    {
        $this->channel = $channel;
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Heartbeat::channel($this->channel)->signal(...$this->options);
    }
}
