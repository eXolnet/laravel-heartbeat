<?php

namespace Exolnet\Heartbeat\Channels;

use GuzzleHttp\Client as HttpClient;

class HttpChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Create a new Slack channel instance.
     *
     * @param  \GuzzleHttp\Client  $http
     * @return void
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $url
     * @param array $options
     * @return void
     */
    public function signal($url, array $options = []): void
    {
        $method = $options['method'] ?? 'get';

        $this->http->request($method, $url, $options);
    }
}
