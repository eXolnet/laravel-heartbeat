<?php

namespace Exolnet\Heartbeat\Tests\Integration;

use Exolnet\Heartbeat\HeartbeatFacade as Heartbeat;
use GuzzleHttp\Client as HttpClient;
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
