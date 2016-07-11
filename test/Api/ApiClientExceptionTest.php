<?php

namespace Ibercheck\Api;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers Ibercheck\Api\ApiClientException
 * @covers Ibercheck\Api\ApiProblem\ApiProblemExceptionTrait
 */
class ApiClientExceptionTest extends TestCase
{
    use ApiProblem\ApiProblemExceptionTestTrait;

    protected function fromResponseCallback()
    {
        return ApiClientException::class . '::fromResponse';
    }
}
