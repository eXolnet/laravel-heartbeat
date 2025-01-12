<?php

namespace Exolnet\Heartbeat\Channels;

use GuzzleHttp\Client as HttpClient;

class NewrelicChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Create a new newrelic channel instance.
     *
     * @param  \GuzzleHttp\Client  $http
     * @return void
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $account
     * @param string $key
     * @param string $eventType
     * @param array $eventData
     * @return voids
     */
    public function signal(string $account, string $key, string $eventType, array $eventData = []): void
    {
        $url = 'https://insights-collector.newrelic.com/v1/accounts/' . $account . '/events';

        $this->http->request('post', $url, [
            'headers' => [
                'Api-Key' => $key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'eventType' => $eventType,
                ...$eventData
            ],
        ]);
    }
}
