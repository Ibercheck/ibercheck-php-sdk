<?php

namespace Ibercheck\Api;

use UnexpectedValueException;

/**
 * This exception is thrown when the data cannot be deserialized/unmarshalled.
 */
class DeserializeException extends UnexpectedValueException implements ApiExceptionInterface
{
}
