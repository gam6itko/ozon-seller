<?php

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V1;

use Gam6itko\OzonSeller\Exception\AccessDeniedException;
use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\ProductValidatorException;
use Gam6itko\OzonSeller\Service\V1\ProductsService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V1\ProductsService
 * @group  v1
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ProductsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function getSvc(): ProductsService
    {
        return new ProductsService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY']/*, $_SERVER['API_URL']*/);
    }

    /**
     * @covers ::classify
     */
    public function testClassify(): void
    {
        $this->expectException(AccessDeniedException::class);
        $json = <<<JSON
{
    "products": [
        {
            "offer_id": "147190464",
            "shop_category_full_path": "Электроника/Телефоны и аксессуары/Смартфоны",
            "shop_category": "Смартфоны",
            "shop_category_id": 15502,
            "vendor": "Apple, Inc",
            "model": "iPhone XS 256GB Space Grey",
            "name": "Смартфон Apple iPhone XS 256GB Space Grey",
            "price": "100990",
            "offer_url": "https://www.ozon.ru/context/detail/id/147190464/",
            "img_url": "https://ozon-st.cdn.ngenix.net/multimedia/1024351473.jpg",
            "vendor_code": "apple_inc",
            "barcode": "190198794017"
        }
    ]
}
JSON;

        $this->getSvc()->classify(json_decode($json, true));
    }

    /**
     * @covers ::import
     */
    public function testImport(): void
    {
        $this->expectException(AccessDeniedException::class);
        $json = <<<JSON
{
    "items": [
        {
            "category_id": "17036198",
            "description": "Description for item",
            "offer_id": "16209",
            "name": "Наушники Apple AirPods 2 (без беспроводной зарядки чехла)",
            "price": 10110,
            "vat": 0,
            "quantity": "3",
            "vendor_code": "AM016209",
            "height": "55",
            "depth": "22",
            "width": "45",
            "dimension_unit": "mm",
            "weight": "8",
            "weight_unit": "g",
            "images": [
                {
                    "file_name": "https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MRXJ2?wid=1144&hei=1144&fmt=jpeg&qlt=95&op_usm=0.5,0.5&.v=1551489675083",
                    "default": true
                }
            ],
            "attributes": [
                {
                    "id": 8229,
                    "value": "193"
                }
            ]
        }
    ]
}
JSON;

        $this->getSvc()->import(json_decode($json, true), true);
    }

    /**
     * @covers ::import
     * @dataProvider dataImportInvalid
     */
    public function testImportInvalid(string $jsonFile): void
    {
        $this->expectException(ProductValidatorException::class);
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = $this->getSvc()->import($input, true);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('product_id', $result);
        self::assertArrayHasKey('state', $result);
    }

    public function dataImportInvalid(): array
    {
        return [
            [__DIR__.'/../../Resources/V1/Products/create.invalid.0.request.json'],
            [__DIR__.'/../../Resources/V1/Products/create.invalid.1.request.json'],
        ];
    }

    /**
     * @covers ::import
     */
    public function testImportException(): void
    {
        try {
            $this->getSvc()->import([], false);
        } catch (BadRequestException $exc) {
            self::assertEmpty($exc->getData()); //todo-ozon-support нет никаких данных
            self::assertEquals('Invalid JSON payload', $exc->getMessage());
        }
    }

    /**
     * @covers ::import
     * @dataProvider dataImportFail
     */
    public function testImportFail(string $jsonFile): void
    {
        try {
            $input = json_decode(file_get_contents($jsonFile), true);
            $this->getSvc()->import($input, false);
        } catch (BadRequestException $exc) {
            self::assertEmpty($exc->getData()); //todo-ozon-support нет никаких данных
            self::assertEquals('Invalid JSON payload', $exc->getMessage());
        }
    }

    public function dataImportFail(): array
    {
        return [
            [__DIR__.'/../../Resources/V1/Products/create.invalid.0.request.json'],
            [__DIR__.'/../../Resources/V1/Products/create.invalid.1.request.json'],
        ];
    }

    /**
     * @covers ::createBySku
     */
    public function testImportBySku(): void
    {
        $this->expectException(AccessDeniedException::class);
        $json = <<<JSON
{
    "items": [
        {
            "sku": 1445625485,
            "name": "Nice boots 1",
            "offer_id": "RED-SHOES-MODEL-1-38-39",
            "price": "7999",
            "old_price": "8999",
            "premium_price": "7555",
            "vat": "0"
        },
        {
            "sku": 1445625485,
            "name": "Nice boots 2",
            "offer_id": "RED-SHOES-MODEL-1-38-39",
            "price": "7999",
            "old_price": "8999",
            "premium_price": "7555",
            "vat": "0"
        }
    ]
}
JSON;

        $this->getSvc()->importBySku(json_decode($json, true));
    }

    /**
     * @covers ::importInfo
     * @depends testImport
     */
    public function testCreationStatus(): void
    {
        $this->expectException(AccessDeniedException::class);
        $status = $this->getSvc()->importInfo(1914378);
        self::assertNotEmpty($status);
        self::assertArrayHasKey('total', $status);
        self::assertArrayHasKey('items', $status);
        self::assertCount(1, $status['items']);
        self::assertArrayHasKey('offer_id', $status['items'][0]);
        self::assertArrayHasKey('product_id', $status['items'][0]);
        self::assertArrayHasKey('status', $status['items'][0]);
    }

    /**
     * @covers ::infoStocks
     */
    public function testInfoStock(): void
    {
        $this->expectException(AccessDeniedException::class);
        $status = $this->getSvc()->infoStocks();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::infoPrices
     */
    public function testInfoPrices(): void
    {
        $this->expectException(AccessDeniedException::class);
        $status = $this->getSvc()->infoPrices();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::list
     */
    public function testList(): void
    {
        $this->expectException(AccessDeniedException::class);
        $result = $this->getSvc()->list();
        self::assertNotEmpty($result);
        self::assertCount(2, $result);
        self::assertArrayHasKey('items', $result);
        self::assertArrayHasKey('total', $result);
        $items = $result['items'];
        self::assertCount(10, $items);
        self::assertArrayHasKey('product_id', $items[0]);
        self::assertArrayHasKey('offer_id', $items[0]);
    }

    /**
     * @covers ::update
     */
    public function testUpdateException(): void
    {
        try {
            $this->getSvc()->update([], false);
        } catch (BadRequestException $exc) {
            self::assertEmpty($exc->getData()); //todo-ozon-support нет никаких данных
            self::assertEquals('Invalid JSON payload', $exc->getMessage());
        }
    }

    /**
     * @covers ::info
     */
    public function testInfo(): void
    {
        $this->expectException(AccessDeniedException::class);
        $productInfo = $this->getSvc()->info(507735);
        self::assertNotEmpty($productInfo);
        self::assertArrayHasKey('name', $productInfo);
    }

    /**
     * @covers ::update
     */
    public function testUpdate(): void
    {
        $this->expectException(AccessDeniedException::class);
        $arr = [
            'product_id' => 507735,
            'images'     => [
                [
                    'file_name' => 'https://images.freeimages.com/images/large-previews/4ad/snare-drum-second-take-1-1564542.jpg',
                    'default'   => true,
                ],
            ],
        ];
        $result = $this->getSvc()->update($arr, false);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('updated', $result);
        self::assertTrue($result['updated']);
    }

    /**
     * @covers ::deactivate
     */
    public function testDeactivate(): void
    {
        $this->expectException(AccessDeniedException::class);
        $result = $this->getSvc()->deactivate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::activate
     */
    public function testActivate(): void
    {
        $this->expectException(AccessDeniedException::class);
        $result = $this->getSvc()->activate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::delete
     * @depends testImport
     */
    public function testDelete(): void
    {
        $this->expectException(AccessDeniedException::class);
        $status = $this->getSvc()->delete(510216);
        self::assertNotEmpty($status);
    }

    public function testUpdatePricesNotFound(): void
    {
        $this->expectException(AccessDeniedException::class);
        $expectedJson = <<<JSON
[
    {
        "product_id": 120000,
        "updated": false,
        "errors": [
            "not_found"
        ]
    },
    {
        "product_id": 124100,
        "updated": false,
        "errors": [
            "not_found"
        ]
    }
]
JSON;
        $arr = [
            [
                'product_id'    => 120000,
                'offer_id'      => 'offer_1',
                'price'         => '79990',
                'old_price'     => '89990',
                'premium_price' => '69990',
                'vat'           => '0.1',
            ],
            [
                'product_id'    => 124100,
                'offer_id'      => 'offer_2',
                'price'         => '79990',
                'old_price'     => '89990',
                'premium_price' => '69990',
                'vat'           => '0.1',
            ],
        ];
        $result = $this->getSvc()->importPrices($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }

    /**
     * @covers ::importPrices
     */
    public function testUpdatePrices(): void
    {
        $this->expectException(AccessDeniedException::class);
        $expectedJson = <<<JSON
[
    {
        "product_id": 508756,
        "updated": true,
        "errors": []
    }
]
JSON;

        $arr = [
            [
                'product_id'    => 508756,
                'offer_id'      => 'PRD-1',
                'price'         => '45000',
                'old_price'     => '40000',
                'premium_price' => '35000',
                'vat'           => '0.2',
            ],
        ];
        $result = $this->getSvc()->importPrices($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }

    /**
     * @covers ::importStocks
     */
    public function testUpdateStocks(): void
    {
        $this->expectException(AccessDeniedException::class);
        $expectedJson = <<<JSON
[
    {
        "product_id": 507735,
        "updated": true,
        "errors": []
    }
]
JSON;

        $arr = [
            [
                'product_id' => 507735,
                'stock'      => 20,
            ],
        ];
        $result = $this->getSvc()->importStocks($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }

    public function testPrice()
    {
        $this->expectException(AccessDeniedException::class);
        $this->getSvc()->price([], ['page' => 1, 'page_size' => 10]);
    }
}
