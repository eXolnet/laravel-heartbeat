<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Exolnet\Heartbeat\Tests\Mocks\CustomChannel;
use Illuminate\Foundation\Application;
use Mockery as m;

class CustomTest extends TestCase
{
    /**
     * @return void
     */
    public function testCustomChannel()
    {
        $channel = m::mock(CustomChannel::class);

        Heartbeat::extend('custom', function ($app) use ($channel) {
            return $channel;
        });

        $channel->shouldReceive('signal')->with('someOption', ['more' => 'options'])->once();

        Heartbeat::custom('someOption', ['more' => 'options']);
    }
}
