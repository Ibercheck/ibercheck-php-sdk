<?php

namespace Ibercheck\Api\ApiProblem;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert as Assert;
use Psr\Http\Message\ResponseInterface;

trait ApiProblemExceptionTestTrait
{
    /**
     * @return callable
     */
    abstract protected function fromResponseCallback();

    /**
     * @dataProvider errorResponseProvider
     */
    public function testErrorResponseThrowException(ResponseInterface $response, $code, $type, $message, $detail): void
    {
        /** @var ApiProblemExceptionTrait $e */
        $e = call_user_func($this->fromResponseCallback(), $response);

        Assert::assertSame($detail, $e->getDetail(), 'getDetail not match');
        Assert::assertSame($type, $e->getType(), 'getType not match');
        Assert::assertSame($message, $e->getTitle(), 'getTitle not match');
        Assert::assertSame($message, $e->getMessage(), 'getMessage not match');
        Assert::assertSame($code, $e->getStatus(), 'getStatus not match');
        Assert::assertSame($code, $e->getCode(), 'getCode not match');
    }

    public function errorResponseProvider(): array
    {
        $basicErrorResponse = [
            'title' => 'errorMessage',
            'type' => 'errorType',
            'status' => 400,
            'detail' => ['fooDetail'],
        ];

        return [
            // Description => [$payload, $code, $type, $message, $detail]
            'api problem payload' => [new Response(400, [], json_encode($basicErrorResponse)), 400, 'errorType', 'errorMessage', ['fooDetail']],
            'empty payload' => [new Response(400), 400, '', 'Bad Request', ''],
            'invalid payload' => [new Response(400, [], '<html>'), 400, '', 'Bad Request', '<html>'],
        ];
    }
}
