<?php

namespace Ibercheck\Http;

/**
 * @covers \Ibercheck\Http\GuzzleClient
 */
class GuzzleClientTest extends AbstractClientTestCase
{
    protected function setUpClient()
    {
        return new GuzzleClient();
    }
}
