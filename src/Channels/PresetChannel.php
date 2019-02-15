<?php

namespace Exolnet\Heartbeat\Channels;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

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
        $config  = $this->getPreset($preset);
        $name    = $config['channel'] ?? null;
        $channel = Heartbeat::channel($name);

        $this->app->call([$channel, 'signal'], $config);
    }

    /**
     * @param string $preset
     * @return array
     */
    protected function getPreset($preset)
    {
        $config = $this->app->make('config')->get('heartbeat.presets.'. $preset);

        if (! is_array($config)) {
            throw new InvalidArgumentException('Unknown heartbeat preset "'. $preset .'".');
        }

        return $config;
    }
}
