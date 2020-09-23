<?php

namespace Exolnet\Heartbeat;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Manager;

class HeartbeatManager extends Manager
{
    /**
     * Get a channel instance.
     *
     * @param  string  $name
     * @return mixed
     */
    public function channel(string $name)
    {
        return $this->driver($name);
    }

    /**
     * Create an instance of the disk driver.
     *
     * @return \Exolnet\Heartbeat\Channels\DiskChannel
     */
    protected function createDiskDriver(): Channels\DiskChannel
    {
        return $this->getContainer()->make(Channels\DiskChannel::class);
    }

    /**
     * Create an instance of the file driver.
     *
     * @return \Exolnet\Heartbeat\Channels\FileChannel
     */
    protected function createFileDriver(): Channels\FileChannel
    {
        return $this->getContainer()->make(Channels\FileChannel::class);
    }

    /**
     * Create an instance of the HTTP driver.
     *
     * @return \Exolnet\Heartbeat\Channels\HttpChannel
     */
    protected function createHttpDriver(): Channels\HttpChannel
    {
        return $this->getContainer()->make(Channels\HttpChannel::class);
    }

    /**
     * Create an instance of the preset driver.
     *
     * @return \Exolnet\Heartbeat\Channels\PresetChannel
     */
    protected function createPresetDriver(): Channels\PresetChannel
    {
        return $this->getContainer()->make(Channels\PresetChannel::class);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        throw new HeartbeatException('Heartbeat does not support default driver/channel.');
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return void
     */
    public function __call($method, $parameters): void
    {
        $this->channel($method)->signal(...$parameters);
    }

    /**
     * @return \Illuminate\Contracts\Container\Container
     */
    protected function getContainer(): Container
    {
        return $this->container ?? $this->app;
    }
}
