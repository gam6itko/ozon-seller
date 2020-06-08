<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V2\Posting\FboService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\FboService
 * @group  v2
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class FboServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        self::$svc = new FboService((int) $_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function testList()
    {
        self::$svc->list(SortDirection::ASC, 0, 10, ['since' => new \DateTime('2019-01-01'), 'to' => new \DateTime('2020-01-01')]);
        self::assertTrue(true);
    }

    public function testGet()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->get('123456790');
    }
}
