<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use GuzzleHttp\Client as HttpClient;
use Mockery as m;

class HttpTest extends TestCase
{
    /**
     * @return void
     */
    public function testHttpSignal()
    {
        $http = m::mock(HttpClient::class);
        $http->shouldReceive('request')->with('get', 'https://beats.envoyer.io/heartbeat/example', [])->once();

        $this->app[HttpClient::class] = $http;

        Heartbeat::http('https://beats.envoyer.io/heartbeat/example');
    }
}
