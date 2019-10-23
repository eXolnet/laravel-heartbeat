<?php

namespace Exolnet\Heartbeat;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Exolnet\Heartbeat\Heartbeat
 */
class HeartbeatFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'heartbeat';
    }
}
