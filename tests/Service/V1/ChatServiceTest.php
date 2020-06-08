<?php

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\ChatService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class ChatServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ChatService::class;
    }

    public function testList(): void
    {
        $this->quickTest(
            'list',
            [
                [
                    'chat_id_list' => [
                        '6639ec81-616e-480d-82b6-111dec41f674',
                        '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
                    ],
                    'page'         => 1,
                    'page_size'    => 100,
                ],
            ],
            [
                'POST',
                '/v1/chat/list',
                ['body' => '{"chat_id_list":["6639ec81-616e-480d-82b6-111dec41f674","3cdf5407-9f90-4752-8105-8f1d4cd427f5"],"page":1,"page_size":100}'],
            ]
        );
    }

    public function testHistory(): void
    {
        $this->quickTest(
            'history',
            [
                '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
                ['from_message_id' => '986714', 'limit' => 10, 'foo' => 'bar'],
            ],
            [
                'POST',
                '/v1/chat/history',
                ['body' => '{"from_message_id":"986714","limit":10,"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5"}'],
            ]
        );
    }

    public function testSendMessage(): void
    {
        $this->quickTest(
            'sendMessage',
            [
                '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
                'Test Message',
            ],
            [
                'POST',
                '/v1/chat/send/message',
                ['body' => '{"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5","text":"Test Message"}'],
            ],
            '{"result": "success"}',
            static function ($result): void {
                self::assertTrue($result);
            }
        );
    }

    public function testSendFile(): void
    {
        $this->quickTest(
            'sendFile',
            [
                'MSwgMiwgMwo=',
                '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
                'test.txt',
            ],
            [
                'POST',
                '/v1/chat/send/file',
                ['body' => '{"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5","base64_content":"MSwgMiwgMwo=","name":"test.txt"}'],
            ],
            '{"result": "success"}',
            static function ($result): void {
                self::assertTrue($result);
            }
        );
    }

    public function testStart(): void
    {
        $this->quickTest(
            'start',
            [598586936],
            [
                'POST',
                '/v1/chat/start',
                ['body' => '{"order_id":598586936}'],
            ],
            '{"result": {"chat_id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5"}}',
            static function ($result): void {
                self::assertEquals('3cdf5407-9f90-4752-8105-8f1d4cd427f5', $result);
            }
        );

    }
}
