<?php

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Service\ChatService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  \Gam6itko\OzonSeller\Service\ChatService
 * @group  v1
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ChatServiceTest extends TestCase
{
    /** @var ChatService */
    private static $svc;

    private static $chatId;

    public static function setUpBeforeClass()
    {
        self::$svc = new ChatService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp()
    {
        sleep(1); //fix 429 Too Many Requests
    }

    /**
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testSendMessage()
    {
        self::$svc->start(123456);
    }

    /**
     * @depends testSendMessage
     */
    public function testList()
    {
        $result = self::$svc->list();
        self::assertNotEmpty($result);
    }
}
