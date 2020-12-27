<?php

declare(strict_types=1);

namespace Ibercheck\Api;

use LogicException;

/**
 * Exception used by HTTP 4xx client errors.
 */
class ApiClientException extends LogicException implements ApiExceptionInterface, ApiProblem\ApiProblemInterface
{
    use ApiProblem\ApiProblemExceptionTrait;
}
