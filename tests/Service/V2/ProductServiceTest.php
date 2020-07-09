<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2;

use Gam6itko\OzonSeller\Service\V2\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

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
                '{"items":[{"barcode":"string","category_id":0,"depth":0,"dimension_unit":"cm","height":0,"image_group_id":"string","images":["string"],"images360":["string"],"offer_id":0,"old_price":"string","pdf_list":[{"index":0,"name":"string","src_url":"string"}],"premium_price":"string","price":"string","vat":"string","weight":0,"weight_unit":"g","width":0,"attributes":[{"complex_id":0,"id":0,"values":[{"dictionary_value_id":0,"value":"string"}]}],"complex_attributes":[{"attributes":[{"complex_id":0,"id":0,"values":[{"dictionary_value_id":0,"value":"string"}]}]}]}]}',
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
}
