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

    public static function setUpBeforeClass()
    {
        self::$svc = new ChatService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], 'http://cb-api.test.ozon.ru/');
    }

    public function testList()
    {
        $result = self::$svc->list();
        self::assertEmpty($result);
    }
}