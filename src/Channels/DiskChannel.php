<?php

namespace Exolnet\Heartbeat\Channels;

use Illuminate\Contracts\Filesystem\Factory;

class DiskChannel
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Factory
     */
    protected $filesystem;

    /**
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystem
     */
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $file
     * @param string|null $disk
     * @return void
     */
    public function signal($file, $disk = null): void
    {
        $this->filesystem->disk($disk)->put($file, now()->toDateTimeString());
    }
}
