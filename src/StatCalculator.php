<?php

namespace SwgohHelp;

use Storage;
use GuzzleHttp\Client;
use JsonStreamingParser\Parser;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\StreamWrapper;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use JsonStreamingParser\Listener\InMemoryListener;

class StatCalculator {

    const API_ENDPOINT = 'api';

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    function addStatsTo($player, $flags = 'gameStyle,statIDs') {
        $query = is_null($flags) ? '' : "?flags=$flags";
        try {
            $response = $this->getHttpClient()->post($this->getURL(static::API_ENDPOINT) . $query, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => collect($player)->toArray(),
            ]);
            $raw = $response->getBody();
            $response = null;
            unset($response);
            $listener = new InMemoryListener;
            $parser = new Parser(StreamWrapper::getResource($raw), $listener);
            $parser->parse();

            return collect($listener->getJson());
        } catch (ClientException | ServerException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() == 401 || $body['code'] == 401 || $response->getStatusCode() == 503) {
                $this->setToken(null);
                $args = func_get_args();
                return call_user_func_array([$this, __METHOD__], $args);
            }

            throw $e;
        }
    }

    function getURL($api) {
        return config('services.swgoh_stats.url', 'https://crinolo-swgoh.glitch.me/statCalc/') . '/' . $api;
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;

        return $this;
    }
}