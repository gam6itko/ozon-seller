<?php

use Gam6itko\OzonSeller\Service\ChatService;
use PHPUnit\Framework\TestCase;

/**
 * @covers ChatService
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

    public function testSendMessage()
    {
        $chatId = self::$svc->start($_SERVER['CHAT_ORDER_ID']);
        self::assertNotEmpty($chatId);
        self::$chatId = $chatId;
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