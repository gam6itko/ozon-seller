<?php

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Service\ProductsService;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\ProductsService
 */
class ProductsServiceTest extends \PHPUnit\Framework\TestCase
{
    public function getSvc(): ProductsService
    {
        return new ProductsService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY']/*, $_SERVER['API_URL']*/);
    }

    /**
     * @covers ::classify
     * @dataProvider dataClassify
     *
     * @param string $jsonFile
     * @param string $responseFile
     */
    public function testClassify(string $jsonFile, string $responseFile): void
    {
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = $this->getSvc()->classify($input);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonFile($responseFile, json_encode($result));
    }

    public function dataClassify(): array
    {
        return [
            [__DIR__.'/../Resources/Products/classify.0.request.json', __DIR__.'/../Resources/Products/classify.0.response.json'],
        ];
    }

    /**
     * @covers ::create
     * @dataProvider dataCreate
     *
     * @param string $jsonFile
     */
    public function testCreate(string $jsonFile): void
    {
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = $this->getSvc()->create($input, true);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('task_id', $result);
    }

    public function dataCreate(): array
    {
        return [
            [__DIR__.'/../Resources/Products/create.0.request.json'],
        ];
    }

    /**
     * @covers ::create
     * @dataProvider dataCreateInvalid
     * @expectedException \Gam6itko\OzonSeller\Exception\ProductValidatorException
     *
     * @param string $jsonFile
     */
    public function testCreateInvalid(string $jsonFile): void
    {
        $input = json_decode(file_get_contents($jsonFile), true);
        $result = $this->getSvc()->create($input, true);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('product_id', $result);
        self::assertArrayHasKey('state', $result);
    }

    public function dataCreateInvalid(): array
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
        $result = $this->getSvc()->create([], false);
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
        $result = $this->getSvc()->create($input, false);
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
     * @covers ::createBySku
     * @dataProvider dataCreateBySku
     *
     * @param string $jsonFileIncome
     */
    public function testCreateBySku(string $jsonFileIncome):void
    {
        $input = json_decode(file_get_contents($jsonFileIncome), true);
        $result = $this->getSvc()->createBySku($input);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('task_id', $result);
        self::assertArrayHasKey('unmatched_sku_list', $result);
    }

    public function dataCreateBySku(string $jsonFileIncome)
    {
        return [
            [__DIR__.'/../Resources/Products/create-by-sku.fail.0.request.json']
        ];
    }

    /**
     * @covers ::creationStatus
     * @depends testCreate
     */
    public function testCreationStatus(): void
    {
        $status = $this->getSvc()->creationStatus(1914378);
        self::assertNotEmpty($status);
        self::assertArrayHasKey('total', $status);
        self::assertArrayHasKey('items', $status);
        self::assertCount(1, $status['items']);
        self::assertArrayHasKey('offer_id', $status['items'][0]);
        self::assertArrayHasKey('product_id', $status['items'][0]);
        self::assertArrayHasKey('status', $status['items'][0]);
    }

    /**
     * @covers ::stockInfo
     */
    public function testStockInfo(): void
    {
        $status = $this->getSvc()->stockInfo();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::pricesInfo
     */
    public function testPricesInfo(): void
    {
        $status = $this->getSvc()->pricesInfo();
        self::assertNotEmpty($status);
    }

    /**
     * @covers ::list
     *
     * @throws \Exception
     */
    public function testList(): void
    {
        $result = $this->getSvc()->list([], ['page' => 1, 'page_size' => 10]);
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
        $result = $this->getSvc()->update([], false);
        self::assertNotEmpty($result);
    }

    /**
     * @covers ::info
     * @depends testUpdate
     */
    public function testInfo(): void
    {
        $productInfo = $this->getSvc()->info(507735);
        self::assertNotEmpty($productInfo);
        self::assertArrayHasKey('name', $productInfo);
    }

    /**
     * @covers ::update
     *
     * @throws \Exception
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
        $result = $this->getSvc()->deactivate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::deactivate
     * @depends testDeactivate
     */
    public function testActivate(): void
    {
        $result = $this->getSvc()->activate(510216);
        self::assertTrue($result);
    }

    /**
     * @covers ::delete
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $status = $this->getSvc()->delete(510216);
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
        $result = $this->getSvc()->updatePrices($arr);
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
        $result = $this->getSvc()->updatePrices($arr);
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
        $result = $this->getSvc()->updateStocks($arr);
        self::assertNotEmpty($result);
        self::assertJsonStringEqualsJsonString($expectedJson, \GuzzleHttp\json_encode($result));
    }
}
