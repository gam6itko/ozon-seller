<?php

use Gam6itko\OzonSeller\Service\ProductsService;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\ProductsService
 */
class ProductsServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProductsService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        self::$svc = new ProductsService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    /**
     * @covers ::create
     * @dataProvider dataCreate
     * @expectedException \Gam6itko\OzonSeller\Exception\ProductValidatorException
     *
     * @param string $jsonFile
     */
    public function testCreate(string $jsonFile): void
    {
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = self::$svc->create($input, true);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('product_id', $result);
        self::assertArrayHasKey('state', $result);
    }

    public function dataCreate(): array
    {
        return [
            [__DIR__.'/../Resources/Products/create.invalid.0.request.json'],
            [__DIR__.'/../Resources/Products/create.invalid.1.request.json'],
        ];
    }

    /**
     * @covers ::create
     * @expectedException \Gam6itko\OzonSeller\Exception\BadRequestException
     */
    public function testCreateException(): void
    {
        $result = self::$svc->create([], false);
        self::assertNotEmpty($result);
    }

    /**
     * @covers ::create
     * @expectedException \Gam6itko\OzonSeller\Exception\BadRequestException
     * @dataProvider dataCreateFail
     *
     * @param string $jsonFile
     */
    public function testCreateFail(string $jsonFile): void
    {
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = self::$svc->create($input, false);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('product_id', $result);
        self::assertArrayHasKey('state', $result);
    }

    public function dataCreateFail(): array
    {
        return [
            [__DIR__.'/../Resources/Products/create.fail.0.request.json'],
            [__DIR__.'/../Resources/Products/create.invalid.1.request.json'],
            [__DIR__.'/../Resources/Products/create.invalid.0.request.json'],
        ];
    }

    /**
     * @covers ::creationStatus
     * @depends testCreate
     */
    public function testCreationStatus(): void
    {
        $status = self::$svc->creationStatus(33919);
        self::assertNotEmpty($status);
        self::assertArrayHasKey('items', $status);
        self::assertArrayHasKey('total', $status);
    }

    /**
     * @covers ::stockInfo
     */
    public function testStockInfo(): void
    {
        $status = self::$svc->stockInfo();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::pricesInfo
     */
    public function testPricesInfo(): void
    {
        $status = self::$svc->pricesInfo();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::list
     *
     * @throws Exception
     */
    public function testList(): void
    {
        $result = self::$svc->list([], ['page' => 1, 'page_size' => 10]);
        self::assertNotEmpty($result);
        self::assertCount(10, $result);
        self::assertArrayHasKey('product_id', $result[0]);
        self::assertArrayHasKey('offer_id', $result[0]);
    }

    /**
     * @covers ::update
     * @expectedException \Gam6itko\OzonSeller\Exception\ValidationException
     */
    public function testUpdateException(): void
    {
        $result = self::$svc->update([], false);
        self::assertNotEmpty($result);
    }

    /**
     * @covers ::info
     * @depends testUpdate
     */
    public function testInfo(): void
    {
        $productInfo = self::$svc->info(507735);
        self::assertNotEmpty($productInfo);
        self::assertArrayHasKey('name', $productInfo);
    }

    /**
     * @covers ::update
     *
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $arr = [
            'product_id' => 507735,
            'images'     => [
                [
                    'file_name' => 'https://images.freeimages.com/images/large-previews/4ad/snare-drum-second-take-1-1564542.jpg',
                    'default'   => true,
                ],
            ],
        ];
        $result = self::$svc->update($arr, false);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('updated', $result);
        self::assertTrue($result['updated']);
    }

    /**
     * @covers ::deactivate
     */
    public function testDeactivate(): void
    {
        $result = self::$svc->deactivate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::deactivate
     * @depends testDeactivate
     */
    public function testActivate(): void
    {
        $result = self::$svc->activate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::delete
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $status = self::$svc->delete(510216);
        self::assertNotEmpty($status);
    }

    public function testUpdatePricesNotFound(): void
    {
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
                'product_id' => 120000,
                'price'      => '79990',
                'old_price'  => '89990',
                'vat'        => '0.10',
            ],
            [
                'product_id' => 124100,
                'price'      => '79990',
                'old_price'  => '89990',
                'vat'        => '0.18',
            ],
        ];
        $result = self::$svc->updatePrices($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }

    /**
     * @covers ::updatePrices
     */
    public function testUpdatePrices(): void
    {
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
                'product_id' => 508756,
                'price'      => '45000',
                'vat'        => '0.18',
            ],
        ];
        $result = self::$svc->updatePrices($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }

    /**
     * @covers ::updateStocks
     */
    public function testUpdateStocks(): void
    {
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
        $result = self::$svc->updateStocks($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }
}
