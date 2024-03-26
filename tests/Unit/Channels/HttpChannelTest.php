<?php

namespace Exolnet\Heartbeat\Tests\Unit\Channels;

use Exolnet\Heartbeat\Channels\HttpChannel;
use Exolnet\Heartbeat\Tests\Unit\UnitTestCase;
use GuzzleHttp\Client as HttpClient;
use Mockery as m;

class HttpChannelTest extends UnitTestCase
{
    /**
     * @var \Mockery\MockInterface|\Illuminate\Filesystem\Filesystem
     */
    protected $httpClient;

    /**
     * @var \Exolnet\Heartbeat\Channels\HttpChannel
     */
    protected $channel;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = m::mock(HttpClient::class);

        $this->channel = new HttpChannel($this->httpClient);
    }

    /**
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(HttpChannel::class, $this->channel);
    }

    /**
     * @return void
     */
    public function testSignal()
    {
        $this->httpClient->shouldReceive('request')->with('get', 'https://foo.bar', []);

        $this->channel->signal('https://foo.bar');

        $this->assertTrue(true);
    }
}
