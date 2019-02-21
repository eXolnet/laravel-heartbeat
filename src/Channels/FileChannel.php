<?php

namespace Exolnet\Heartbeat\Channels;

use Illuminate\Filesystem\Filesystem;

class FileChannel
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $file
     * @return void
     */
    public function signal($file)
    {
        $this->filesystem->put($file, now()->toDateTimeString());
    }
}
