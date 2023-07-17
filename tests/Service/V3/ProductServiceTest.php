<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V3;

use Gam6itko\OzonSeller\Service\V3\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V3\ProductService
 */
final class ProductServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ProductService::class;
    }

    /**
     * @covers ::infoStocks
     */
    public function testList(): void
    {
        $payload = [
            'filter'  => [
                'offer_id'   => ['offer_id'],
                'product_id' => ['offer_id'],
                'visibility' => 'ALL',
            ],
            'last_id' => '1',
            'limit'   => 100,
        ];
        $this->quickTest(
            'infoStocks',
            [
                $payload['filter'],
                $payload['last_id'],
                $payload['limit'],
            ],
            [
                'POST',
                '/v3/product/info/stocks',
                \json_encode($payload),
            ]
        );
    }
}
