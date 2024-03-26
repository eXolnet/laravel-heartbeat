<?php

namespace Exolnet\Heartbeat\Tests\Unit\Channels;

use Exolnet\Heartbeat\Channels\PresetChannel;
use Exolnet\Heartbeat\Tests\Unit\UnitTestCase;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Mockery as m;

class PresetChannelTest extends UnitTestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $app;

    /**
     * @var \Exolnet\Heartbeat\Channels\PresetChannel
     */
    protected $channel;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app = m::mock(Container::class);

        $this->channel = new PresetChannel($this->app);
    }

    /**
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(PresetChannel::class, $this->channel);
    }

    /**
     * @return void
     */
    public function testInvalidPreset()
    {
        $this->expectException(InvalidArgumentException::class);

        $config = m::mock(Repository::class);
        $config->shouldReceive('get')->with('heartbeat.presets.invalid')->andReturn(null);

        $this->app->shouldReceive('make')->with('config')->andReturn($config);

        $this->channel->signal('invalid');
    }
}
