<?php

namespace Ibercheck\Api;

class Sale
{
    public static function createSaleHash(string $affiliateSecret, string $orderNumber, string $productMnemonic): string
    {
        return hash_hmac('sha256', 'newSale' . $orderNumber . $productMnemonic, $affiliateSecret);
    }
}
