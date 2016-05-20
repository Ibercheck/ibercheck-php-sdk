<?php

namespace Ibercheck\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

final class GuzzleClient implements ClientInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function send(RequestInterface $request)
    {
        $response = $this->client->send($request);

        return $response;
    }
}
