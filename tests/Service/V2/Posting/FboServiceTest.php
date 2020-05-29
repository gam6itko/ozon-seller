<?php

namespace Gam6itko\OzonSeller\Tests\Service\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V2\Posting\FboService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\FboService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 * @group v2
 */
class FboServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new FboService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp()
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function testList()
    {
        self::$svc->list(SortDirection::ASC, 0, 10, ['since' => new \DateTime('2019-01-01'), 'to' => new \DateTime('2020-01-01')]);
        self::assertTrue(true);
    }

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testGet()
    {
        self::$svc->get('123456790');
    }
}
