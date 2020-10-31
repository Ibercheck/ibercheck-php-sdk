<?php

declare(strict_types=1);

namespace Ibercheck\Api;

/**
 * This class provides useful methods for to validate Webhooks received from Ibercheck.
 */
class Webhook
{
    public static function isAuthentic(string $affiliateSecret, string $webhookSignature, string $webhookPayload): bool
    {
        return hash_equals(self::calculateSignatureFor($affiliateSecret, $webhookPayload), $webhookSignature);
    }

    protected static function calculateSignatureFor(string $affiliateSecret, string $webhookPayload): string
    {
        return hash_hmac('sha256', $webhookPayload, $affiliateSecret);
    }
}
