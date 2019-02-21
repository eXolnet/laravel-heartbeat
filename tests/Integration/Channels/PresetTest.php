<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Carbon\Carbon;
use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class PresetTest extends TestCase
{
    /**
     * @return void
     */
    public function testPresetSignal()
    {
        $date = Carbon::parse('2019-02-15 08:00:00');
        Carbon::setTestNow($date);

        config()->set('heartbeat.presets.test', [
            'channel' => 'file',
            'file' => '/tmp/heartbeat',
        ]);

        $filesystem = m::mock(Filesystem::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '2019-02-15 08:00:00')->once();

        $this->app[Filesystem::class] = $filesystem;

        Heartbeat::preset('test');
    }
}
