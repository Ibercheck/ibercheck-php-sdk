<?php

namespace Ibercheck\Api;

/**
 * This class provides useful methods for to validate Webhooks received from Ibercheck.
 */
class Webhook
{
    /**
     * @param string $affiliateSecret
     * @param string $webhookSignature
     * @param string $webhookPayload
     *
     * @return bool
     */
    public static function isAuthentic($affiliateSecret, $webhookSignature, $webhookPayload)
    {
        return hash_equals(self::calculateSignatureFor($affiliateSecret, $webhookPayload), $webhookSignature);
    }

    /**
     * @param string $affiliateSecret
     * @param string $webhookPayload
     *
     * @return string Sales signature
     */
    protected static function calculateSignatureFor($affiliateSecret, $webhookPayload)
    {
        return hash_hmac('sha256', $webhookPayload, $affiliateSecret);
    }
}
