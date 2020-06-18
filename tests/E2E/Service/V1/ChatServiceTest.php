<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V1;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Service\V1\ChatService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  \Gam6itko\OzonSeller\Service\V1\ChatService
 * @group  v1
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ChatServiceTest extends TestCase
{
    /** @var ChatService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        $config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
        $adapter = new GuzzleAdapter(new GuzzleClient());
        self::$svc = new ChatService($config, $adapter);
    }

    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function testSendMessage()
    {
        $this->expectException(BadRequestException::class);
        self::$svc->start(123456);
    }

    /**
     * @depends testSendMessage
     */
    public function testList()
    {
        $result = self::$svc->list();
        self::assertTrue(true);
    }
}
