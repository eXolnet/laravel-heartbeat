<?php

namespace Exolnet\Heartbeat\Channels;

use Illuminate\Contracts\Filesystem\Filesystem;

class FileChannel
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Illuminate\Contracts\Filesystem\Filesystem $filesystem
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
