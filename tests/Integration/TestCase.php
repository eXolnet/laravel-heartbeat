<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\HeartbeatServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            HeartbeatServiceProvider::class,
        ];
    }
}
