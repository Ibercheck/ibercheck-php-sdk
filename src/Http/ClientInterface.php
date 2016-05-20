<?php

namespace Ibercheck\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

interface ClientInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws RuntimeException If request cannot be performed due network issues.
     */
    public function send(RequestInterface $request);
}
