<?php

namespace Ibercheck\Api;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers Ibercheck\Api\Sale
 */
class SaleTest extends TestCase
{
    /**
     * @dataProvider saleHashProvider
     */
    public function testCreateSaleHash($affiliateSecret, $orderNumber, $productMnemonic, $signature)
    {
        self::assertEquals($signature, Sale::createSaleHash($affiliateSecret, $orderNumber, $productMnemonic));
    }

    public function saleHashProvider()
    {
        return [
            // Description => [affiliateSecret, orderNumber, productMnemonic, signature]
            // @codingStandardsIgnoreStart
            's3cr3t' => ['s3cr3t', 'n-asdf1234', 'autocheck', 'adbc8f5b536cb92b207e1af40223e6f174f68f00aeac13d4b0e3fad627f30eb4'],
            // @codingStandardsIgnoreEnd
        ];
    }
}
