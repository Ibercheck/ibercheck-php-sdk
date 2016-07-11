<?php

namespace Ibercheck\Api;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers Ibercheck\Api\ApiServerException
 * @covers Ibercheck\Api\ApiProblem\ApiProblemExceptionTrait
 */
class ApiServerExceptionTest extends TestCase
{
    use ApiProblem\ApiProblemExceptionTestTrait;

    protected function fromResponseCallback()
    {
        return ApiServerException::class . '::fromResponse';
    }
}
