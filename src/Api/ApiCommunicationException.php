<?php

namespace Ibercheck\Api;

use Exception;
use RuntimeException;

/**
 * Exception thrown when there is communication possible with the API.
 */
class ApiCommunicationException extends RuntimeException implements ApiExceptionInterface
{
    public static function fromException(Exception $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
