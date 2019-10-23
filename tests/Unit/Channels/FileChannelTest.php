<?php

namespace Exolnet\Heartbeat\Tests\Unit\Channels;

use Carbon\Carbon;
use Exolnet\Heartbeat\Channels\FileChannel;
use Exolnet\Heartbeat\Tests\Unit\UnitTest;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class FileChannelTest extends UnitTest
{
    /**
     * @var \Mockery\MockInterface|\Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Exolnet\Heartbeat\Channels\FileChannel
     */
    protected $channel;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = m::mock(Filesystem::class);

        $this->channel = new FileChannel($this->filesystem);
    }

    /**
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(FileChannel::class, $this->channel);
    }

    /**
     * @return void
     */
    public function testSignal()
    {
        Carbon::setTestNow('2019-10-23 09:25:23');

        $this->filesystem->shouldReceive('put')->with('foo.txt', '2019-10-23 09:25:23');

        $this->channel->signal('foo.txt');

        $this->assertTrue(true);
    }
}
