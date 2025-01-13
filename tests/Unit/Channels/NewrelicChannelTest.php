<?php

namespace Exolnet\Heartbeat\Tests\Unit\Channels;

use Exolnet\Heartbeat\Channels\NewrelicChannel;
use Exolnet\Heartbeat\Tests\Unit\UnitTestCase;
use GuzzleHttp\Client as HttpClient;
use Mockery as m;

class NewrelicChannelTest extends UnitTestCase
{
    /**
     * @var \Mockery\MockInterface|\Illuminate\Filesystem\Filesystem
     */
    protected $httpClient;

    /**
     * @var \Exolnet\Heartbeat\Channels\NewrelicChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $eventType;

    /**
     * @var string
     */
    protected $account;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = m::mock(HttpClient::class);

        $this->channel = new NewrelicChannel($this->httpClient);

        $this->key = '1f2d4c5a6b8e9f0a1b2c3d4e5f6a7b8c';

        $this->eventType = 'HeartbeatEvent';

        $this->account = '587431';

        $this->url = 'https://insights-collector.newrelic.com/v1/accounts/' . $this->account . '/events';
    }

    /**
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(NewrelicChannel::class, $this->channel);
    }

    /**
     * @return void
     */
    public function testSignal()
    {
        $options = [
            'headers' => [
                'Api-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'eventType' => $this->eventType
            ]
        ];

        $this->httpClient->shouldReceive('request')->with('post', $this->url, $options);

        $this->channel->signal($this->account, $this->key, $this->eventType);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testSignalWithAdditionalData()
    {
        $eventData = [
            'appName' => 'test',
            'env' => 'preprod'
        ];

        $options = [
            'headers' => [
                'Api-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'eventType' => $this->eventType,
                ...$eventData
            ]
        ];

        $this->httpClient->shouldReceive('request')->with('post', $this->url, $options);

        $this->channel->signal($this->account, $this->key, $this->eventType, $eventData);

        $this->assertTrue(true);
    }
}
