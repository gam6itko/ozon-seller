<?php

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V1;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Service\V1\ChatService;
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

    private static $chatId;

    public static function setUpBeforeClass(): void
    {
        self::$svc = new ChatService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
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
