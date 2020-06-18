<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V2;

use Gam6itko\OzonSeller\Service\V2\CategoryService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\CategoryService
 * @group  v2
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CategoryServiceTest extends TestCase
{
    /** @var CategoryService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        $config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
        $adapter = new GuzzleAdapter(new GuzzleClient());
        self::$svc = new CategoryService($config, $adapter);
    }

    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    /**
     * @covers ::attribute
     */
    public function testAttribute(): void
    {
        $result = self::$svc->attribute(17033429);
        self::assertNotEmpty($result);
        self::assertIsArray($result);
        self::assertIsArray($result);
        self::assertArrayHasKey('id', $result[0]);
    }

    /**
     * @covers ::attributeValues
     */
    public function testAttributeValues(): void
    {
        $result = self::$svc->attributeValues(17036076, 8229);
        self::assertNotEmpty($result);
        self::assertIsArray($result);
        self::assertNotEmpty($result);
        self::assertArrayHasKey('id', $result[0]);
        self::assertArrayHasKey('value', $result[0]);
    }
}
