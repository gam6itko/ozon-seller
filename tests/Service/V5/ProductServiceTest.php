<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V5;

use Gam6itko\OzonSeller\Enum\Visibility;
use Gam6itko\OzonSeller\Service\V5\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V5\ProductService
 */
class ProductServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ProductService::class;
    }

    /**
     * @covers ::infoPrices
     *
     * @dataProvider dataInfoPrices
     */
    public function testInfoPrices(array $productsFilter, string $expectedJsonString): void
    {
        $this->quickTest(
            'infoPrices',
            $productsFilter,
            [
                'POST',
                '/v5/product/info/prices',
                $expectedJsonString,
            ]
        );
    }

    public function dataInfoPrices(): iterable
    {
        $arguments = [
            'filter' => [
                'visibility' => Visibility::ALL,
            ],
            'cursor' => '',
            'limit'  => 150,
        ];
        $offer_ids = ['3244378', '1107890', 'PRD-1'];
        $product_ids = [243686911];

        $arguments['filter']['offer_id'] = $offer_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"visibility":"ALL"},"cursor":"","limit":150}',
        ];

        unset($arguments['filter']['offer_id']);
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"product_id":[243686911],"visibility":"ALL"},"cursor":"","limit":150}',
        ];

        $arguments['filter']['offer_id'] = $offer_ids;
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"product_id":[243686911],"visibility":"ALL"},"cursor":"","limit":150}',
        ];

        $arguments['filter'] = [];
        unset($arguments['limit']);
        $arguments['cursor'] = 'list_cursor';
        yield [
            $arguments,

            '{"filter":{},"cursor":"list_cursor","limit":100}',
        ];
    }
}
