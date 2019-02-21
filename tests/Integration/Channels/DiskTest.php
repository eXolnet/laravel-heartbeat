<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Carbon\Carbon;
use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemAdapter;
use Mockery as m;

class DiskTest extends TestCase
{
    /**
     * @return void
     */
    public function testDiskSignal()
    {
        $date = Carbon::parse('2019-02-15 08:00:00');
        Carbon::setTestNow($date);

        $filesystem = m::mock(FilesystemAdapter::class);
        $filesystem->shouldReceive('put')->with('disk.heartbeat', '2019-02-15 08:00:00')->once();

        $filesystemFactory = m::mock(Factory::class);
        $filesystemFactory->shouldReceive('disk')->with('local')->once()->andReturn($filesystem);

        $this->app[Factory::class] = $filesystemFactory;

        Heartbeat::disk('disk.heartbeat', 'local');
    }
}
