<?php

namespace Ibercheck\Api;

class Sale
{
    /**
     * @param string $affiliateSecret
     * @param string $orderNumber
     * @param string $productMnemonic
     *
     * @return string Sales signature
     */
    public static function createSaleHash($affiliateSecret, $orderNumber, $productMnemonic)
    {
        return hash_hmac('sha256', 'newSale' . $orderNumber . $productMnemonic, $affiliateSecret);
    }
}
