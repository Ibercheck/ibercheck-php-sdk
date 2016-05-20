<?php

namespace Ibercheck\Api;

use DomainException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * This class provide helpful methods for craft API request and hide the details of manipulate the message syntax.
 */
class ApiRequest extends Request
{
    const API_VERSION = 1;
    const SDK_VERSION = '0.1.0';

    /**
     * @param string $accessToken
     *
     * @return string
     */
    public static function createAuthorizationHeader($accessToken)
    {
        return 'Bearer ' . $accessToken;
    }

    /**
     * @param int $apiVersion
     *
     * @return string
     */
    public static function createAcceptHeader($apiVersion)
    {
        $header = sprintf('application/vnd.ibercheck.v%d+json', $apiVersion);

        return $header;
    }

    /**
     * @param string $method HTTP method for the request.
     * @param string|UriInterface $uri URI for the request.
     * @param array $headers Headers for the message.
     * @param string|resource|StreamInterface $body Message body.
     */
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null
    ) {
        $defaultHeaders = [
            'Accept' => [
                self::createAcceptHeader(self::API_VERSION),
                'application/problem+json',
                'application/json',
            ],
            'User-Agent' => [
                'Ibercheck/' . self::SDK_VERSION,
                'PHP/' . PHP_VERSION,
            ],
        ];

        parent::__construct($method, $uri, $defaultHeaders + $headers, $body);
    }

    /**
     * @param string $accessToken Authorization access token.
     *
     * @return ApiRequest
     */
    public function withAuthentication($accessToken)
    {
        return $this->withHeader('Authorization', self::createAuthorizationHeader($accessToken));
    }

    /**
     * @param string|JsonSerializable|array $data
     *
     * @return ApiRequest
     */
    public function withJsonSerializableData($data)
    {
        $data = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DomainException('Unable to serialize data as JSON: ' . json_last_error_msg(), json_last_error());
        }

        $request = $this->withHeader('Content-Type', 'application/json');
        $request->getBody()->write($data);

        return $request;
    }

    /**
     * @param string $fieldName
     * @param StreamInterface|resource|string $fileContent
     * @param null|string $filename
     *
     * @return ApiRequest
     */
    public function withFile($fieldName, $fileContent, $filename = null)
    {
        $multipartStream = new MultipartStream(
            [
                $fieldName => [
                    'name' => $fieldName,
                    'contents' => $fileContent,
                    'filename' => $filename,
                ],
            ]
        );

        return $this->withHeader('Content-Type', 'multipart/form-data; boundary=' . $multipartStream->getBoundary())
                    ->withBody($multipartStream)
            ;
    }
}
