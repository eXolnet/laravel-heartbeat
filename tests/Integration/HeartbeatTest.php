<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Carbon\Carbon;
use Exolnet\Heartbeat\HeartbeatException;
use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class HeartbeatTest extends TestCase
{
    /**
     * @return void
     */
    public function testHeartbeatIsASingleton()
    {
        $heartbeat1 = $this->app->make(Heartbeat::class);
        $heartbeat2 = $this->app->make(Heartbeat::class);

        $this->assertEquals($heartbeat1, $heartbeat2);
    }

    /**
     * @return void
     */
    public function testDefaultDriverThrowAnException()
    {
        $this->expectException(HeartbeatException::class);

        Heartbeat::driver();
    }

    /**
     * @return void
     */
    public function testCommandNow()
    {
        $date = Carbon::parse('2019-02-15 08:00:00');
        Carbon::setTestNow($date);

        $filesystem = m::mock(Filesystem::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '2019-02-15 08:00:00')->once();

        $this->app[Filesystem::class] = $filesystem;

        $this->artisan('heartbeat', [
            'channel' => 'file',
            'options' => ['/tmp/heartbeat'],
        ]);
    }

    /**
     * @return void
     */
    public function testCommandQueued()
    {
        $date = Carbon::parse('2019-02-15 08:00:00');
        Carbon::setTestNow($date);

        $filesystem = m::mock(Filesystem::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '2019-02-15 08:00:00')->once();

        $this->app[Filesystem::class] = $filesystem;

        $this->artisan('heartbeat', [
            '--queue' => true,
            'channel' => 'file',
            'options' => ['/tmp/heartbeat'],
        ]);
    }
}
