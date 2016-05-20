<?php

namespace Ibercheck\Api;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers Ibercheck\Api\Webhook
 */
class WebhookTest extends TestCase
{
    /**
     * @dataProvider webhookProvider
     */
    public function testIsAuthentic($affiliateSecret, $webhookSignature, $webhookPayload)
    {
        self::assertTrue(Webhook::isAuthentic($affiliateSecret, $webhookSignature, $webhookPayload));
    }

    public function webhookProvider()
    {
        return [
            // Description => [affiliateSecret, webhookSignature, webhookPayload]
            // @codingStandardsIgnoreStart
            's3cr3t' => ['s3cr3t', '596f6838cb5b28a84a62bff63003269e4326a0f00e4e4444b3ae812fb62f91c6', 'foo'],
            // @codingStandardsIgnoreEnd
        ];
    }
}
