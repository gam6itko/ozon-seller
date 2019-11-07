<?php

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Enum\DeliverySchema;
use Gam6itko\OzonSeller\Service\OrderService;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\OrderService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class OrderServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var OrderService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new OrderService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    public function testListCrossborder()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::CROSSBOARDER);
        self::assertNotEmpty($response);
    }

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testListFbo()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBO);
        self::assertNotEmpty($response);
    }

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testListFbs()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBS);
        self::assertNotEmpty($response);
    }

    /**
     * @covers \OrderService::itemsCancelReasons
     */
    public function testItemsCancelReasons()
    {
        $result = self::$svc->itemsCancelReasons();
        self::assertNotEmpty($result);
    }

    public function testUnfulfilled()
    {
        $result = self::$svc->unfulfilled();
        self::assertNotEmpty($result);

        self::assertArrayHasKey('order_id', $result[0]);
        self::assertArrayHasKey('items', $result[0]);
    }

    public function testCanceled()
    {
        $result = self::$svc->canceled();
        self::assertTrue(true);
    }

    public function testShippingProviders()
    {
        $result = self::$svc->shippingProviders();
        self::assertTrue(true);
    }
}
