<?php

namespace Ibercheck\Api;

use Exception;
use RuntimeException;

/**
 * Exception thrown when there is communication possible with the API.
 */
class ApiCommunicationException extends RuntimeException implements ApiExceptionInterface
{
    /**
     * @param Exception $exception
     *
     * @return self
     */
    public static function fromException(Exception $exception)
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
