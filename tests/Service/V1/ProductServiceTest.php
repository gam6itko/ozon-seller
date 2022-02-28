<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V1\ProductService
 */
class ProductServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ProductService::class;
    }

    public function testClassify(): void
    {
        $input = [
            'offer_id'                => '147190464',
            'shop_category_full_path' => 'Электроника/Телефоны и аксессуары/Смартфоны',
            'shop_category'           => 'Смартфоны',
            'shop_category_id'        => 15502,
            'vendor'                  => 'Apple, Inc',
            'model'                   => 'iPhone XS 256GB Space Grey',
            'name'                    => 'Смартфон Apple iPhone XS 256GB Space Grey',
            'price'                   => '100990',
            'offer_url'               => 'https://www.ozon.ru/context/detail/id/147190464/',
            'img_url'                 => 'https://ozon-st.cdn.ngenix.net/multimedia/1024351473.jpg',
            'vendor_code'             => 'apple_inc',
            'barcode'                 => '190198794017',
            // bad options
            'foo'                     => 'bar',
            'you'                     => 'shall not pass',
        ];
        $this->quickTest(
            'classify',
            [$input],
            [
                'POST',
                '/v1/product/classify',
                '{"products":[{"offer_id":"147190464","shop_category_full_path":"\u042d\u043b\u0435\u043a\u0442\u0440\u043e\u043d\u0438\u043a\u0430\/\u0422\u0435\u043b\u0435\u0444\u043e\u043d\u044b \u0438 \u0430\u043a\u0441\u0435\u0441\u0441\u0443\u0430\u0440\u044b\/\u0421\u043c\u0430\u0440\u0442\u0444\u043e\u043d\u044b","shop_category":"\u0421\u043c\u0430\u0440\u0442\u0444\u043e\u043d\u044b","shop_category_id":15502,"vendor":"Apple, Inc","model":"iPhone XS 256GB Space Grey","name":"\u0421\u043c\u0430\u0440\u0442\u0444\u043e\u043d Apple iPhone XS 256GB Space Grey","price":"100990","offer_url":"https:\/\/www.ozon.ru\/context\/detail\/id\/147190464\/","img_url":"https:\/\/ozon-st.cdn.ngenix.net\/multimedia\/1024351473.jpg","vendor_code":"apple_inc","barcode":"190198794017"}]}',
            ]
        );
    }

    /**
     * @dataProvider dataImport
     */
    public function testImport(array $input): void
    {
        $this->quickTest(
            'import',
            [$input],
            [
                'POST',
                '/v1/product/import',
                '{"items":[{"barcode":"8801643566784","description":"Red Samsung Galaxy S9 with 512GB","category_id":17030819,"name":"Samsung Galaxy S9","offer_id":"REDSGS9-512","price":"79990","old_price":"89990","premium_price":"75555","vat":"0","vendor":"Samsung","vendor_code":"SM-G960UZPAXAA","height":77,"depth":11,"width":120,"dimension_unit":"mm","weight":120,"weight_unit":"g","images":[{"file_name":"https:\/\/ozon-st.cdn.ngenix.net\/multimedia\/c1200\/1022555115.jpg","default":true},{"file_name":"https:\/\/ozon-st.cdn.ngenix.net\/multimedia\/c1200\/1022555110.jpg","default":false}],"attributes":[{"id":8229,"value":"4747"},{"id":4413,"collection":["1","2","13"]}]}]}',
            ]
        );
    }

    public function dataImport(): iterable
    {
        $item = [
            'barcode'        => '8801643566784',
            'description'    => 'Red Samsung Galaxy S9 with 512GB',
            'category_id'    => 17030819,
            'name'           => 'Samsung Galaxy S9',
            'offer_id'       => 'REDSGS9-512',
            'price'          => '79990',
            'old_price'      => '89990',
            'premium_price'  => '75555',
            'vat'            => '0',
            'vendor'         => 'Samsung',
            'vendor_code'    => 'SM-G960UZPAXAA',
            'height'         => 77,
            'depth'          => 11,
            'width'          => 120,
            'dimension_unit' => 'mm',
            'weight'         => 120,
            'weight_unit'    => 'g',
            'images'         => [
                ['file_name' => 'https://ozon-st.cdn.ngenix.net/multimedia/c1200/1022555115.jpg', 'default' => true],
                ['file_name' => 'https://ozon-st.cdn.ngenix.net/multimedia/c1200/1022555110.jpg', 'default' => false],
            ],
            'attributes'     => [
                ['id' => 8229, 'value' => '4747'],
                ['id' => 4413, 'collection' => ['1', '2', '13']],
            ],
        ];

        yield [$item];
        yield [
            ['items' => [$item]],
        ];
    }

    /**
     * @dataProvider dataImportBySku
     */
    public function testImportBySku(array $input): void
    {
        $this->quickTest(
            'importBySku',
            [$input],
            [
                'POST',
                '/v1/product/import-by-sku',
                '{"items":[{"sku":1445625485,"name":"Nice boots 1","offer_id":"RED-SHOES-MODEL-1-38-39","price":"7999","old_price":"8999","premium_price":"7555","vat":"0"}]}',
            ]
        );
    }

    public function dataImportBySku(): iterable
    {
        $item = [
            'sku'           => 1445625485,
            'name'          => 'Nice boots 1',
            'offer_id'      => 'RED-SHOES-MODEL-1-38-39',
            'price'         => '7999',
            'old_price'     => '8999',
            'premium_price' => '7555',
            'vat'           => '0',
        ];

        yield [$item];
        yield [
            ['items' => [$item]],
        ];
    }

    public function testImportInfo(): void
    {
        $this->quickTest(
            'importInfo',
            [33919],
            [
                'POST',
                '/v1/product/import/info',
                '{"task_id":33919}',
            ]
        );
    }

    public function testInfo(): void
    {
        $this->quickTest(
            'info',
            [7154396],
            [
                'POST',
                '/v1/product/info',
                '{"product_id":7154396}',
            ]
        );
    }

    public function testInfoBy(): void
    {
        $query = [
            'product_id' => 7154396,
            'offer_id'   => 'item_6060091',
            'sku'        => 150583609,
        ];
        $this->quickTest(
            'infoBy',
            [$query],
            [
                'POST',
                '/v1/product/info',
                '{"product_id":7154396,"offer_id":"item_6060091","sku":150583609}',
            ]
        );
    }

    public function testInfoStocks(): void
    {
        $this->quickTest(
            'infoStocks',
            [],
            [
                'POST',
                '/v1/product/info/stocks',
                '{"page":1,"page_size":100}',
            ]
        );
    }

    public function testInfoPrices(): void
    {
        $this->quickTest(
            'infoPrices',
            [],
            [
                'POST',
                '/v1/product/info/prices',
                '{"page":1,"page_size":100}',
            ]
        );
    }

    /**
     * @covers ::list
     * @dataProvider dataList
     */
    public function testList(array $filters, array $pagination, string $json): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "items": [
      {
        "product_id": 124100,
        "offer_id": "REDSGS10-128"
      },
      {
        "product_id": 124201,
        "offer_id": "REDSGS10-512"
      }
    ],
    "total": 2
  }
}
JSON;

        $this->quickTest(
            'list',
            [$filters, $pagination],
            [
                'POST',
                '/v1/product/list',
                $json,
            ],
            $responseJson
        );
    }

    public function dataList(): iterable
    {
        yield [
            [
                'offer_id'   => ['1255959'],
                'product_id' => [552526],
                'visibility' => 'ALL',
            ],
            [],
            '{"page":1,"page_size":10,"filter":{"offer_id":["1255959"],"product_id":[552526],"visibility":"ALL"}}',
        ];

        yield [
            [
                'offer_id'   => '1255959',
                'product_id' => 552526,
                'visibility' => 'ALL',
            ],
            [
                'page'      => 10,
                'page_size' => 100,
            ],
            '{"page":10,"page_size":100,"filter":{"offer_id":["1255959"],"product_id":[552526],"visibility":"ALL"}}',
        ];

        yield [
            [
                'filter'    => [
                    'offer_id'   => ['1255959'],
                    'product_id' => [552526],
                    'visibility' => 'ALL',
                ],
                'page'      => 10,
                'page_size' => 100,
            ],
            [],
            '{"page":10,"page_size":100,"filter":{"offer_id":["1255959"],"product_id":[552526],"visibility":"ALL"}}',
        ];

        yield [
            [
                'page'      => 10,
                'page_size' => 100,
            ],
            [],
            '{"page":10,"page_size":100}',
        ];
    }

    /**
     * @covers ::list
     */
    public function testListOfferIdType(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "items": [
      {
        "product_id": 1,
        "offer_id": "1"
      },
      {
        "product_id": 2,
        "offer_id": "2"
      },
      {
        "product_id": 3,
        "offer_id": "3"
      }
    ],
    "total": 3
  }
}
JSON;

        $filters = [
            'offer_id' => [1, 2, 3],
        ];
        $this->quickTest(
            'list',
            [$filters],
            [
                'POST',
                '/v1/product/list',
                '{"page":1,"page_size":10,"filter":{"offer_id":["1","2","3"]}}',
            ],
            $responseJson
        );
    }

    /**
     * @covers ::importPrices
     * @dataProvider dataImportPrices
     */
    public function testImportPrices(array $prices, string $expectedJson): void
    {
        $this->quickTest(
            'importPrices',
            [$prices],
            [
                'POST',
                '/v1/product/import/prices',
                $expectedJson
            ]
        );
    }

    public function dataImportPrices(): iterable
    {
        $item = [
            'product_id'    => 120000,
            'offer_id'      => 'PRD-1',
            'price'         => '79990',
            'old_price'     => '89990',
            'premium_price' => '75555',
        ];
        $json = '{"prices":[{"product_id":120000,"offer_id":"PRD-1","price":"79990","old_price":"89990","premium_price":"75555"}]}';

        yield [$item, $json];
        yield [[$item], $json];
        yield [['prices' => [$item]], $json];

        yield 'no discount' => [
            [
                'offer_id'      => 'PRD-2',
                'price'         => '10000.10',
                'old_price'     => '5000.50',
            ],
            '{"prices":[{"offer_id":"PRD-2","price":"10000.10","old_price":"0"}]}'
        ];
    }

    /**
     * @dataProvider dataFailImportPrices
     */
    public function testFailImportPrices(array $prices): void
    {
        self::expectException(\InvalidArgumentException::class);

        $config = [123, 'api-key'];
        $client = $this->createMock(ClientInterface::class);
        $svc = new ProductService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->importPrices($prices);
    }

    public function dataFailImportPrices(): iterable
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

    /**
     * @dataProvider dataImportStocks
     */
    public function testImportStocks(array $stocks): void
    {
        $this->quickTest(
            'importStocks',
            [$stocks],
            [
                'POST',
                '/v1/product/import/stocks',
                '{"stocks":[{"product_id":120000,"offer_id":"PRD-1","stock":20}]}',
            ]
        );
    }

    public function dataImportStocks(): iterable
    {
        $stock = [
            'product_id' => 120000,
            'offer_id'   => 'PRD-1',
            'stock'      => 20,
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
        $svc = new ProductService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
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

    public function testUpdate()
    {
        $item = [
            'product_id'     => 124100,
            'barcode'        => '8801643566784',
            'description'    => 'Red Samsung Galaxy S10 with 512GB',
            'name'           => 'Samsung Galaxy S10',
            'vendor'         => 'Samsung',
            'vendor_code'    => 'SM-G960UZPAXAA',
            'height'         => 77,
            'depth'          => 11,
            'width'          => 120,
            'dimension_unit' => 'mm',
            'weight'         => 120,
            'weight_unit'    => 'g',
            'images'         => [
                ['file_name' => 'http://pic.com/1.jpg', 'default' => true],
                ['file_name' => 'http://pic.com/2.jpg', 'default' => false],
            ],
            'attributes'     => [
                ['id' => 1, 'value' => 'Samsung Galaxy S10'],
                ['id' => 2, 'collection' => ['128GB', '512GB']],
            ],
        ];
        $this->quickTest(
            'update',
            [$item],
            [
                'POST',
                '/v1/product/update',
                '{"product_id":124100,"barcode":"8801643566784","description":"Red Samsung Galaxy S10 with 512GB","name":"Samsung Galaxy S10","vendor":"Samsung","vendor_code":"SM-G960UZPAXAA","height":77,"depth":11,"width":120,"dimension_unit":"mm","weight":120,"weight_unit":"g","images":[{"file_name":"http:\/\/pic.com\/1.jpg","default":true},{"file_name":"http:\/\/pic.com\/2.jpg","default":false}],"attributes":[{"id":1,"value":"Samsung Galaxy S10"},{"id":2,"collection":["128GB","512GB"]}]}',
            ]
        );
    }

    public function testSetPayment(): void
    {
        $data = [
            'is_prepayment' => true,
            'offers_ids'    => ['Offer_RbtbQseqtTeBlHB8AjF9t-23'],
            'products_ids'  => [5376526],
        ];
        $this->quickTest(
            'setPrepayment',
            [$data],
            [
                'POST',
                '/v1/product/prepayment/set',
                '{"is_prepayment":true,"offers_ids":["Offer_RbtbQseqtTeBlHB8AjF9t-23"],"products_ids":[5376526]}',
            ]
        );
    }
}
