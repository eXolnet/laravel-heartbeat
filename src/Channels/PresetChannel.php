<?php

namespace Exolnet\Heartbeat\Channels;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Contracts\Container\Container;

class PresetChannel
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    private $app;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $preset
     * @return void
     */
    public function signal($preset)
    {
        $config  = config('heartbeat.presets.'. $preset);

        if (! is_array($config)) {
            throw new \InvalidArgumentException('Unknown heartbeat preset "'. $preset .'".');
        }

        $name    = $config['channel'] ?? null;
        $channel = Heartbeat::channel($name);

        $this->app->call([$channel, 'signal'], $config);
    }
}
