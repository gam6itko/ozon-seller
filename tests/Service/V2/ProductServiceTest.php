<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2;

use Gam6itko\OzonSeller\Service\V2\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\ProductService
 */
class ProductServiceTest extends AbstractTestCase
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
                '/v2/product/import',
                '{"items":[{"barcode":"string","category_id":0,"depth":0,"dimension_unit":"cm","height":0,"image_group_id":"string","images":["string"],"images360":["string"],"name":"string","offer_id":"string","old_price":"string","pdf_list":[{"index":0,"name":"string","src_url":"string"}],"premium_price":"string","price":"string","vat":"string","weight":0,"weight_unit":"g","width":0,"attributes":[{"complex_id":0,"id":0,"values":[{"dictionary_value_id":0,"value":"string"}]}],"complex_attributes":[{"attributes":[{"complex_id":0,"id":0,"values":[{"dictionary_value_id":0,"value":"string"}]}]}]}]}',
            ]
        );
    }

    public function dataImport()
    {
        $item = [
            'description'        => 'text of description',
            'barcode'            => 'string',
            'category_id'        => 0,
            'depth'              => 0,
            'dimension_unit'     => 'cm',
            'height'             => 0,
            'image_group_id'     => 'string',
            'images'             => [
                'string',
            ],
            'images360'          => [
                'string',
            ],
            'name'               => 'string',
            'offer_id'           => 'string',
            'old_price'          => 'string',
            'pdf_list'           => [
                [
                    'index'   => 0,
                    'name'    => 'string',
                    'src_url' => 'string',
                ],
            ],
            'premium_price'      => 'string',
            'price'              => 'string',
            'vat'                => 'string',
            'weight'             => 0,
            'weight_unit'        => 'g',
            'width'              => 0,
            'attributes'         => [
                [
                    'complex_id' => 0,
                    'id'         => 0,
                    'values'     => [
                        [
                            'dictionary_value_id' => 0,
                            'value'               => 'string',
                        ],
                    ],
                ],
            ],
            'complex_attributes' => [
                [
                    'attributes' => [
                        [
                            'complex_id' => 0,
                            'id'         => 0,
                            'values'     => [
                                [
                                    'dictionary_value_id' => 0,
                                    'value'               => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        yield [$item];
        yield [[$item]];
        yield [['items' => [$item]]];
    }

    public function testInfo(): void
    {
        $this->quickTest(
            'info',
            [
                ['offer_id' => 'item_6060091', 'product_id' => 7154396, 'sku' => 150583609],
            ],
            [
                'POST',
                '/v2/product/info',
                '{"offer_id":"item_6060091","product_id":7154396,"sku":150583609}',
            ]
        );
    }

    public function testInfoAttributes(): void
    {
        $this->quickTest(
            'infoAttributes',
            [
                [
                    'offer_id'   => ['ABC-123'],
                    'product_id' => [2346321],
                ],
                0,
                10,
            ],
            [
                'POST',
                '/v2/products/info/attributes',
                '{"filter":{"offer_id":["ABC-123"],"product_id":[2346321]},"page":0,"page_size":10}',
            ]
        );
    }

    /**
     * @covers ::importStocks
     * @dataProvider dataImportStocks
     */
    public function testImportStocks(array $stocks): void
    {
        $this->quickTest(
            'importStocks',
            [$stocks],
            [
                'POST',
                '/v2/products/stocks',
                '{"stocks":[{"product_id":120000,"offer_id":"PRD-1","stock":20,"warehouse_id":22043923995000}]}',
            ]
        );
    }

    public function dataImportStocks(): iterable
    {
        $stock = [
            'product_id' => 120000,
            'offer_id'   => 'PRD-1',
            'stock'      => 20,
            'warehouse_id' => 22043923995000
        ];

        yield [$stock];
        yield [[$stock]];
        yield [['stocks' => [$stock]]];
    }

    /**
     * @dataProvider dataFailImportStocks
     */
    public function testFailImportStocks(array $prices): void
    {
        self::expectException(\InvalidArgumentException::class);

        $config = [123, 'api-key'];
        $client = $this->createMock(ClientInterface::class);
        $svc = new \Gam6itko\OzonSeller\Service\V1\ProductService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->importStocks($prices);
    }

    public function dataFailImportStocks(): iterable
    {
        $item = [
            'foo'     => 'far',
            'bad_key' => 'bad_value',
            2,
            3,
        ];

        yield [$item];
        yield [[]];
        yield [['some_key' => [$item]]];
    }

    public function testInfoStocks(): void
    {
        $this->quickTest(
            'infoStocks',
            [],
            [
                'POST',
                '/v2/product/info/stocks',
                '{"page":1,"page_size":100}',
            ]
        );
    }

    /**
     * @covers ::delete
     * @dataProvider dataDelete
     */
    public function testDelete(array $products, string $expectedJsonString): void
    {
        $this->quickTest(
            'delete',
            [$products],
            [
                'POST',
                '/v2/products/delete',
                $expectedJsonString,
            ]
        );
    }

    public function dataDelete(): iterable
    {
        yield [
            [
                'products' => [
                    ['offer_id' => 'PRD-1'],
                    ['offer_id' => 2],
                ],
            ],

            '{"products":[{"offer_id":"PRD-1"}, {"offer_id":"2"}]}',
        ];

        yield [
            [
                ['offer_id' => 'PRD-1'],
                ['offer_id' => 2],
            ],
            '{"products":[{"offer_id":"PRD-1"}, {"offer_id":"2"}]}',
        ];

        yield [
            ['offer_id' => 'PRD-1'],
            '{"products":[{"offer_id":"PRD-1"}]}',
        ];
    }
}
