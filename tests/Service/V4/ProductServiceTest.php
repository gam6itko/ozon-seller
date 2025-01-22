<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V4;

use Gam6itko\OzonSeller\Enum\Visibility;
use Gam6itko\OzonSeller\Service\V4\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V4\ProductService
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
                '/v4/product/info/prices',
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
            'lastId' => '',
            'limit'  => 100,
        ];
        $offer_ids = ['3244378', '1107890', 'PRD-1'];
        $product_ids = [243686911];

        $arguments['filter']['offer_id'] = $offer_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"visibility":"ALL"},"last_id":"","limit":100}',
        ];

        unset($arguments['filter']['offer_id']);
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"product_id":[243686911],"visibility":"ALL"},"last_id":"","limit":100}',
        ];

        $arguments['filter']['offer_id'] = $offer_ids;
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"product_id":[243686911],"visibility":"ALL"},"last_id":"","limit":100}',
        ];

        $arguments['filter'] = [];
        yield [
            $arguments,

            '{"filter":{},"last_id":"","limit":100}',
        ];
    }

    /**
     * @covers ::infoStocks
     *
     * @dataProvider dataInfoStocks
     */
    public function testInfoStocks(array $productsFilter, string $expectedJsonString): void
    {
        $this->quickTest(
            'infoStocks',
            $productsFilter,
            [
                'POST',
                '/v4/product/info/stocks',
                $expectedJsonString,
            ]
        );
    }

    public function dataInfoStocks(): iterable
    {
        $arguments = [
            'filter' => [
                'visibility' => Visibility::ALL,
            ],
            'cursor' => '',
            'limit'  => 100,
        ];
        $offer_ids = ['3244378', '1107890', 'PRD-1'];
        $product_ids = [243686911];
        $with_quant = ['created' => true, 'exists' => true];

        $arguments['filter']['offer_id'] = $offer_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"visibility":"ALL"},"cursor":"","limit":100}',
        ];

        unset($arguments['filter']['offer_id']);
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"product_id":[243686911],"visibility":"ALL"},"cursor":"","limit":100}',
        ];

        $arguments['filter']['offer_id'] = $offer_ids;
        $arguments['filter']['product_id'] = $product_ids;
        $arguments['cursor'] = 'stocks_cursor';
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"product_id":[243686911],"visibility":"ALL"},"cursor":"stocks_cursor","limit":100}',
        ];

        unset($arguments['filter']['product_id']);
        $arguments['filter']['offer_id'] = $offer_ids;
        $arguments['filter']['with_quant'] = $with_quant;
        $arguments['cursor'] = 'stocks_cursor';
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"visibility":"ALL","with_quant":{"created":true,"exists":true}},"cursor":"stocks_cursor","limit":100}',
        ];

        $arguments['filter'] = [];
        $arguments['cursor'] = '';
        yield [
            $arguments,

            '{"filter":{},"cursor":"","limit":100}',
        ];
    }
}
