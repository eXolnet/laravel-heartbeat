<?php

namespace Exolnet\Heartbeat\Tests\Mocks;

class CustomChannel
{
    /**
     * Send a heartbeat signal.
     *
     * @param string $someOption
     * @param array $moreOptions
     * @return void
     */
    public function signal($someOption, array $moreOptions = [])
    {
        // Send the signal according to the specified options.
    }
}
