<?php

declare(strict_types=1);

namespace Ibercheck\Api;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ibercheck\Api\ApiRequest
 */
class ApiRequestTest extends TestCase
{
    /**
     * @dataProvider acceptHeaderProvider
     */
    public function testCreateAcceptHeader($apiVersion, $expectedHeader): void
    {
        $header = ApiRequest::createAcceptHeader($apiVersion);

        self::assertSame($expectedHeader, $header);
    }

    public function acceptHeaderProvider(): array
    {
        return [
            // Description => [apiVersion, expectedHeader]
            'v1' => [1, 'application/vnd.ibercheck.v1+json'],
            'v2' => [2, 'application/vnd.ibercheck.v2+json'],
        ];
    }

    /**
     * @dataProvider authorizationHeaderProvider
     */
    public function testCreateAuthorizationHeader($accessToken, $expectedHeader): void
    {
        $header = ApiRequest::createAuthorizationHeader($accessToken);

        self::assertSame($expectedHeader, $header);
    }

    public function authorizationHeaderProvider(): array
    {
        return [
            // Description => [accessToken, expectedHeader]
            'foo' => ['foo', 'Bearer foo'],
        ];
    }

    public function testConstructor(): void
    {
        $expectedHeaders = [
            'Accept' => [
                'application/vnd.ibercheck.v1+json',
                'application/problem+json',
                'application/json',
            ],
            'User-Agent' => [
                'Ibercheck/' . ApiRequest::SDK_VERSION,
                'PHP/' . PHP_VERSION,
            ],
        ];

        $helper = new ApiRequest('foo_method', 'foo_uri');

        self::assertSame($expectedHeaders, $helper->getHeaders(), 'getHeaders not match');
    }
}
