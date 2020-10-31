<?php

namespace Ibercheck\Http;

use GuzzleHttp\Psr7\Request;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\RequestInterface;
use RuntimeException;

abstract class AbstractClientTestCase extends TestCase
{
    public function testImplementsClientInterface()
    {
        $transport = $this->setUpClient();

        self::assertInstanceOf(ClientInterface::class, $transport);
    }

    /**
     * @dataProvider requestProvider
     */
    public function testSend(RequestInterface $request)
    {
        $transport = $this->setUpClient();

        $response = $transport->send($request);
        $responseBody = json_decode((string) $response->getBody(), true);

        self::assertSame((string) $request->getUri(), $responseBody['url']);
        self::assertArrayHasKey('X-Foo', $responseBody['headers']);
        self::assertSame('fooValue', $responseBody['headers']['X-Foo']);
        $body = (string) $request->getBody();
        if (!empty($body)) {
            self::assertSame($body, $responseBody['data']);
        }
    }

    /**
     * @dataProvider invalidRequestProvider
     */
    public function testSendThrowException(RequestInterface $request, $exceptionClass)
    {
        $transport = $this->setUpClient();

        $this->setExpectedException($exceptionClass);

        $transport->send($request);
    }

    public function requestProvider()
    {
        $testBody = 'testBody';

        $givenHeaders = ['X-Foo' => ['fooValue']];

        return [
            // Description => [request]
            'delete' => [new Request('delete', 'http://httpbin.org/delete', $givenHeaders)],
            'get' => [new Request('get', 'http://httpbin.org/get', $givenHeaders)],
            'patch' => [new Request('patch', 'http://httpbin.org/patch', $givenHeaders, $testBody)],
            'post' => [new Request('post', 'http://httpbin.org/post', $givenHeaders, $testBody)],
            'put' => [new Request('put', 'http://httpbin.org/put', $givenHeaders, $testBody)],
        ];
    }

    public function invalidRequestProvider()
    {
        return [
            // Description => [request, exceptionClass]
            'Bad host' => [new Request('get', 'http://notexists'), RuntimeException::class],
        ];
    }

    /**
     * @return ClientInterface
     */
    abstract protected function setUpClient();
}
