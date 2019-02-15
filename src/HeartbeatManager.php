<?php

namespace Exolnet\Heartbeat;

use Illuminate\Support\Manager;

class HeartbeatManager extends Manager
{
    /**
     * The default channel used to deliver signals.
     *
     * @var string
     */
    protected $defaultChannel = 'http';

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param array $options
     * @return void
     */
    public function signal(...$options)
    {
        $this->driver()->signal(...$options);
    }

    /**
     * Get a channel instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function channel($name = null)
    {
        return $this->driver($name);
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
        return $this->defaultChannel;
    }

    /**
     * Get the default channel driver name.
     *
     * @return string
     */
    public function deliversVia()
    {
        return $this->getDefaultDriver();
    }

    /**
     * Set the default channel driver name.
     *
     * @param  string  $channel
     * @return void
     */
    public function deliverVia($channel)
    {
        $this->defaultChannel = $channel;
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
