<?php

namespace Gam6itko\OzonSeller\Tests\Service\V2;

use Gam6itko\OzonSeller\Service\V2\ProductService;
use PHPUnit\Framework\TestCase;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\ProductService
 */
class ProductServiceTest extends TestCase
{
    protected function setUp()
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function getSvc(): ProductService
    {
        return new ProductService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY']/*, $_SERVER['API_URL']*/);
    }

    public function testImport()
    {
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

        $this->getSvc()->import(json_decode($json, true), false);
    }

    /**
     * @covers ::info
     * @expectedException \Gam6itko\OzonSeller\Exception\AccessDeniedException
     */
    public function testInfo(): void
    {
        $productInfo = $this->getSvc()->info(['product_id' => 507735]);
        self::assertNotEmpty($productInfo);
        self::assertArrayHasKey('name', $productInfo);
    }

//    public function testInfoAttributes()
//    {
//        $productInfo = $this->getSvc()->infoAttributes(['product_id' => 507735]);
//        self::assertNotEmpty($productInfo);
//        self::assertArrayHasKey('name', $productInfo);
//    }
}
