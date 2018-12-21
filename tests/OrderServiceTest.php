<?php

use Gam6itko\OzonSeller\Enum\DeliverySchema;
use Gam6itko\OzonSeller\Service\OrderService;

/**
 * @covers OrderService
 */
class OrderServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var OrderService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new OrderService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY']);;
    }

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

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testListFbo()
    {
        self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBO);
    }

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testListFbs()
    {
        self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBS);
    }

    public function testListCrossborder()
    {
        self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::CROSSBOARDER);
        self::assertTrue(true);
    }
}