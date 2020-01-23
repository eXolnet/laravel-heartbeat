<?php

namespace Exolnet\Heartbeat\Tests\Unit\Channels;

use Carbon\Carbon;
use Exolnet\Heartbeat\Channels\DiskChannel;
use Exolnet\Heartbeat\Tests\Unit\UnitTest;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Mockery as m;

class DiskChannelTest extends UnitTest
{
    /**
     * @var \Mockery\MockInterface|\Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * @var \Exolnet\Heartbeat\Channels\DiskChannel
     */
    protected $channel;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = m::mock(Filesystem::class);

        $this->channel = new DiskChannel($this->filesystem);
    }

    /**
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(DiskChannel::class, $this->channel);
    }

    /**
     * @return void
     */
    public function testSignal()
    {
        Carbon::setTestNow(
            Carbon::parse('2019-10-23 09:25:23')
        );

        $this->filesystem->shouldReceive('disk->put')->with('foo.txt', '2019-10-23 09:25:23');

        $this->channel->signal('foo.txt');
    }
}
