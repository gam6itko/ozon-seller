<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V3;

use Gam6itko\OzonSeller\Enum\Visibility;
use Gam6itko\OzonSeller\Service\V3\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;

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
     * @dataProvider dataImport
     */
    public function testImport(array $item): void
    {
        $this->quickTest(
            'import',
            [$item],
            [
                'POST',
                '/v3/product/import',
                '{"items":[{"offer_id":"test_offer","description_category_id":17028922,"name":"Test Product","price":"1000","old_price":"1100","currency_code":"RUB","vat":"0.1","depth":10,"width":150,"height":250,"dimension_unit":"mm","weight":100,"weight_unit":"g","barcode":"112772873170","attributes":[{"complex_id":0,"id":5076,"values":[{"dictionary_value_id":971082156,"value":"Test Attribute"}]}],"images":[],"images360":[],"primary_image":"","color_image":"","complex_attributes":[],"pdf_list":[],"promotions":[]}]}',
            ]
        );
    }

    public function dataImport()
    {
        $correctItem = [
            'offer_id' => 'test_offer',
            'description_category_id' => 17028922,
            'name' => 'Test Product',
            'price' => '1000',
            'old_price' => '1100',
            'currency_code' => 'RUB',
            'vat' => '0.1',
            'depth' => 10,
            'width' => 150,
            'height' => 250,
            'dimension_unit' => 'mm',
            'weight' => 100,
            'weight_unit' => 'g',
            'barcode' => '112772873170',
            'attributes' => [
                [
                    'complex_id' => 0,
                    'id' => 5076,
                    'values' => [
                        [
                            'dictionary_value_id' => 971082156,
                            'value' => 'Test Attribute'
                        ]
                    ]
                ]
            ],
            'images' => [],
            'images360' => [],
            'primary_image' => '',
            'color_image' => '',
            'complex_attributes' => [],
            'pdf_list' => [],
            'promotions' => []
        ];

        yield [$correctItem];
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

    /**
     * @covers ::list
     *
     * @dataProvider dataList
     */
    public function testProductList(array $methodArguments, string $expectedJsonString): void
    {
        $this->quickTest(
            'list',
            $methodArguments,
            [
                'POST',
                '/v3/product/list',
                $expectedJsonString,
            ]
        );
    }

    public function dataList(): iterable
    {
        $arguments = [
            'filter'  => [
                'visibility' => Visibility::ALL,
            ],
            'lastId'  => '',
            'limit'   => 150,
        ];
        $offer_ids = ['3244378', '1107890', 'PRD-1'];
        $product_ids = [243686911];

        $arguments['filter']['offer_id'] = $offer_ids;
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"visibility":"ALL"},"last_id":"","limit":150}',
        ];

        unset($arguments['filter']['offer_id']);
        $arguments['filter']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"filter":{"product_id":[243686911],"visibility":"ALL"},"last_id":"","limit":150}',
        ];

        $arguments['filter']['offer_id'] = $offer_ids;
        $arguments['filter']['product_id'] = $product_ids;
        $arguments['lastId'] = 'last_id_value';
        yield [
            $arguments,

            '{"filter":{"offer_id":["3244378","1107890","PRD-1"],"product_id":[243686911],"visibility":"ALL"},"last_id":"last_id_value","limit":150}',
        ];

        $arguments['filter'] = [];
        $arguments['lastId'] = '';
        yield [
            $arguments,

            '{"filter":{},"last_id":"","limit":150}',
        ];
    }

    /**
     * @covers ::infoList
     *
     * @dataProvider dataInfoList
     */
    public function testProductInfoList(array $methodArguments, string $expectedJsonString): void
    {
        $this->quickTest(
            'infoList',
            $methodArguments,
            [
                'POST',
                '/v3/product/info/list',
                $expectedJsonString,
            ]
        );
    }

    public function dataInfoList(): iterable
    {
        $arguments = [
            'query'  => [
            ],
        ];
        $offer_ids = ['3244378', '1107890', 'PRD-1'];
        $product_ids = [243686911];
        $skus = [12686911, 82372623121];

        $arguments['query']['offer_id'] = $offer_ids;
        yield [
            $arguments,

            '{"offer_id":["3244378","1107890","PRD-1"]}',
        ];

        unset($arguments['query']['offer_id']);
        $arguments['query']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"product_id":["243686911"]}',
        ];

        $arguments['query']['offer_id'] = $offer_ids;
        $arguments['query']['product_id'] = $product_ids;
        yield [
            $arguments,

            '{"offer_id":["3244378","1107890","PRD-1"],"product_id":["243686911"]}',
        ];

        $arguments['query'] = ['sku' => $skus];
        yield [
            $arguments,

            '{"sku":["12686911","82372623121"]}',
        ];
    }

    /**
     * @covers ::infoList
     */
    public function testProductInfoListException(): void
    {
        self::expectException(\InvalidArgumentException::class);

        $config = [123, 'api-key'];
        $client = $this->createMock(ClientInterface::class);
        $svc = new ProductService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->infoList([]);
    }
}
