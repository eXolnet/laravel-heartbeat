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
    public function testHeartbeatIsASingleton()
    {
        $heartbeat1 = $this->app->make(Heartbeat::class);
        $heartbeat2 = $this->app->make(Heartbeat::class);

        $this->assertEquals($heartbeat1, $heartbeat2);
    }

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

    /**
     * @return void
     */
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

    public function testCommandNow()
    {
        $filesystem = m::mock(FilesystemAdapter::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '')->once();

        $this->app[Filesystem::class] = $filesystem;

        $this->artisan('heartbeat', [
            'channel' => 'file',
            'options' => ['/tmp/heartbeat'],
        ]);
    }

    public function testCommandQueued()
    {
        $filesystem = m::mock(FilesystemAdapter::class);
        $filesystem->shouldReceive('put')->with('/tmp/heartbeat', '')->once();

        $this->app[Filesystem::class] = $filesystem;

        $this->artisan('heartbeat', [
            '--queue' => true,
            'channel' => 'file',
            'options' => ['/tmp/heartbeat'],
        ]);
    }
}
