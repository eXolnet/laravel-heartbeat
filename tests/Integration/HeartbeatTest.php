<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Mockery as m;

class HeartbeatTest extends TestCase
{
    /**
     * @return void
     */
    public function testFileSignal()
    {

        $filesystem = m::mock(FilesystemAdapter::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '')->once();

        $this->app[Filesystem::class] = $filesystem;

        Heartbeat::file('/tmp/heartbeat');
    }

    public function testPresetSignal()
    {
        config()->set('heartbeat.presets.test', [
            'channel' => 'file',
            'file' => '/tmp/heartbeat',
        ]);

        $filesystem = m::mock(FilesystemAdapter::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '')->once();

        $this->app[Filesystem::class] = $filesystem;

        Heartbeat::preset('test');
    }
}
