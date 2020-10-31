<?php

declare(strict_types=1);

namespace Ibercheck\Api;

use RuntimeException;

/**
 * Exception used by HTTP 5xx client errors.
 */
class ApiServerException extends RuntimeException implements ApiExceptionInterface, ApiProblem\ApiProblemInterface
{
    use ApiProblem\ApiProblemExceptionTrait;
}
