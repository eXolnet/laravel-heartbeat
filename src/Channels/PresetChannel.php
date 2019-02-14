<?php

namespace Exolnet\Heartbeat\Channels;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;

class PresetChannel
{
    /**
     * @param string $preset
     * @return void
     */
    public function signal($preset)
    {
        $config  = config('heartbeat.presets.'. $preset);
        $channel = $config['channel'] ?? null;
        $options = (array)($config['channel'] ?? []);

        Heartbeat::channel($channel)->signal(...$options);
    }
}
