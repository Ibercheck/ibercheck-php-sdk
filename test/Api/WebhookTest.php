<?php

declare(strict_types=1);

namespace Ibercheck\Api;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ibercheck\Api\Webhook
 */
class WebhookTest extends TestCase
{
    /**
     * @dataProvider webhookProvider
     */
    public function testIsAuthentic($affiliateSecret, $webhookSignature, $webhookPayload): void
    {
        self::assertTrue(Webhook::isAuthentic($affiliateSecret, $webhookSignature, $webhookPayload));
    }

    public function webhookProvider(): array
    {
        return [
            // Description => [affiliateSecret, webhookSignature, webhookPayload]
            // @codingStandardsIgnoreStart
            's3cr3t' => ['s3cr3t', '596f6838cb5b28a84a62bff63003269e4326a0f00e4e4444b3ae812fb62f91c6', 'foo'],
            // @codingStandardsIgnoreEnd
        ];
    }
}
