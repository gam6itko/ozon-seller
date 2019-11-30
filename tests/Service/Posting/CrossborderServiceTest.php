<?php

namespace Gam6itko\OzonSeller\Tests\Service\Posting;

use Gam6itko\OzonSeller\Service\CategoriesService;
use Gam6itko\OzonSeller\Service\Posting\CrossborderService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\Posting\CrossborderService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 * @group v2
 */
class CrossborderServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new CrossborderService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    /**
     * @covers ::list
     */
    public function testList()
    {
        self::$svc->list(new \DateTime('2019-01-01'), new \DateTime('2020-01-01'));
        self::assertTrue(true);
    }

    /**
     * @covers ::get
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testGet()
    {
        self::$svc->get('123456790');
    }

    /**
     * @covers ::unfulfilledList
     */
    public function testUnfulfilledList()
    {
        self::$svc->unfulfilledList();
        self::assertTrue(true);
    }

    /**
     * @covers ::approve
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testApprove()
    {
        self::$svc->approve('123456');
        self::assertTrue(true);
    }

    /**
     * @covers ::cancel
     * @expectedException \Gam6itko\OzonSeller\Exception\BadRequestException
     */
    public function testCancel()
    {
        self::$svc->cancel('39268230-0002-3', '149123456', 349, 'Cancel reason');
        self::assertTrue(true);
    }

    /**
     * @covers ::ship
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testShip()
    {
        self::$svc->ship('39268230-0002-3', 'AB123456CD', 15109877837000, [
            [
                'quantity' => 2,
                'sku'      => 100056,
            ],
        ]);
        self::assertTrue(true);
    }
}
