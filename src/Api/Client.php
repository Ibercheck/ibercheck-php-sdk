<?php

namespace Ibercheck\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This class provides useful methods for to perform requests against Ibercheck's API.
 */
class Client
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ApiCommunicationException if an I/O error occurs.
     * @throws ApiServerException if an I/O error occurs.
     * @throws ApiClientException if request is invalid.
     */
    public function sendRequest(RequestInterface $request)
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw ApiCommunicationException::fromException($e);
        }

        if ($response->getStatusCode() >= 500) {
            throw ApiServerException::fromResponse($response);
        }

        if ($response->getStatusCode() >= 400) {
            throw ApiClientException::fromResponse($response);
        }

        return $response;
    }

    /**
     * @param string $responseBody
     *
     * @return array
     *
     * @throws DeserializeException if response cannot be deserialized.
     */
    public function decodeResponseBody($responseBody)
    {
        // Response body is empty for HTTP 204 and 304 status code.
        if (empty($responseBody)) {
            return [];
        }

        $responseBody = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DeserializeException('Unable to deserialize JSON data: ' . json_last_error_msg(), json_last_error());
        }

        return $responseBody;
    }
}
