<?php

namespace Ibercheck\Api;

use Closure;
use GuzzleHttp\Psr7\Response;
use Ibercheck\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \Ibercheck\Api\Client
 */
class ClientTest extends TestCase
{
    /**
     * @dataProvider wrongResponseProvider
     */
    public function testSendRequestThrowException(Closure $httpClientCallable, $exception)
    {
        $httpClientCallable = $httpClientCallable->bindTo($this);

        $client = new Client($httpClientCallable());

        $this->expectException($exception);

        /** @var RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);

        $response = $client->sendRequest($request);
        $client->decodeResponseBody((string) $response->getBody());
    }

    public function wrongResponseProvider()
    {
        $apiCommunication = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willThrowException(new ApiCommunicationException());

            return $httpClient;
        };
        $deserialization = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(200, [], '<html>'));

            return $httpClient;
        };
        $clientException = function () {
            $body = json_encode([
                'title' => 'errorMessage',
                'type' => 'errorType',
                'status' => 400,
                'detail' => ['fooDetail'],
            ]);

            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(400, [], $body));

            return $httpClient;
        };
        $clientExceptionWithoutBody = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(400));

            return $httpClient;
        };
        $clientExceptionWithInvalidBody = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(400, [], '<html>'));

            return $httpClient;
        };

        $serverException = function () {
            $body = json_encode([
                'title' => 'errorMessage',
                'type' => 'errorType',
                'status' => 500,
                'detail' => ['fooDetail'],
            ]);

            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(500, [], $body));

            return $httpClient;
        };
        $serverExceptionWithoutBody = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(500));

            return $httpClient;
        };
        $serverExceptionWithInvalidBody = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(500, [], '<html>'));

            return $httpClient;
        };

        return [
            // Description => [client callable, exception]
            'Communication' => [$apiCommunication, ApiCommunicationException::class],
            'Deserialization' => [$deserialization, DeserializeException::class],
            'ClientException' => [$clientException, ApiClientException::class],
            'ClientExceptionWithoutBody' => [$clientExceptionWithoutBody, ApiClientException::class],
            'ClientExceptionWithInvalidBody' => [$clientExceptionWithInvalidBody, ApiClientException::class],
            'ServerException' => [$serverException, ApiServerException::class],
            'ServerExceptionWithoutBody' => [$serverExceptionWithoutBody, ApiServerException::class],
            'ServerExceptionWithInvalidBody' => [$serverExceptionWithInvalidBody, ApiServerException::class],
        ];
    }

    /**
     * @dataProvider validResponseProvider
     */
    public function testSendRequest(Closure $httpClientCallable, $expectedResult)
    {
        $httpClientCallable = $httpClientCallable->bindTo($this);

        $client = new Client($httpClientCallable());

        /** @var RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);

        $response = $client->sendRequest($request);
        $result = $client->decodeResponseBody((string) $response->getBody());

        self::assertSame($expectedResult, $result);
    }

    public function validResponseProvider()
    {
        $jsonSchema = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(200, [], '{"foo": "baz"}'));

            return $httpClient;
        };

        $empty = function () {
            $httpClient = $this->createMock(ClientInterface::class);
            $httpClient->method('send')->willReturn(new Response(204, []));

            return $httpClient;
        };

        return [
            // Description => [client callable, result]
            'json schema' => [$jsonSchema, ['foo' => 'baz']],
            'empty' => [$empty, []],
        ];
    }
}
