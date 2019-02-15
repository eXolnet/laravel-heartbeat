<?php

namespace Exolnet\Heartbeat;

use Illuminate\Support\Manager;

class HeartbeatManager extends Manager
{
    /**
     * Get a channel instance.
     *
     * @param  string  $name
     * @return mixed
     */
    public function channel($name)
    {
        return $this->driver($name);
    }

    /**
     * Create an instance of the disk driver.
     *
     * @return \Exolnet\Heartbeat\Channels\DiskChannel
     */
    protected function createDiskDriver()
    {
        return $this->app->make(Channels\DiskChannel::class);
    }

    /**
     * Create an instance of the file driver.
     *
     * @return \Exolnet\Heartbeat\Channels\FileChannel
     */
    protected function createFileDriver()
    {
        return $this->app->make(Channels\FileChannel::class);
    }

    /**
     * Create an instance of the HTTP driver.
     *
     * @return \Exolnet\Heartbeat\Channels\HttpChannel
     */
    protected function createHttpDriver()
    {
        return $this->app->make(Channels\HttpChannel::class);
    }

    /**
     * Create an instance of the preset driver.
     *
     * @return \Exolnet\Heartbeat\Channels\PresetChannel
     */
    protected function createPresetDriver()
    {
        return $this->app->make(Channels\PresetChannel::class);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new HeartbeatException('Heartbeat does not support default driver/channel.');
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        $this->channel($method)->signal(...$parameters);
    }
}
