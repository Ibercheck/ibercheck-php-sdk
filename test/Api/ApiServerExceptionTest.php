<?php

namespace Ibercheck\Api;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ibercheck\Api\ApiServerException
 * @covers \Ibercheck\Api\ApiProblem\ApiProblemExceptionTrait
 */
class ApiServerExceptionTest extends TestCase
{
    use ApiProblem\ApiProblemExceptionTestTrait;

    protected function fromResponseCallback()
    {
        return ApiServerException::class . '::fromResponse';
    }
}
