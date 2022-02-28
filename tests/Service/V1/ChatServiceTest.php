<?php

declare(strict_types=1);

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
                '{"chat_id_list":["6639ec81-616e-480d-82b6-111dec41f674","3cdf5407-9f90-4752-8105-8f1d4cd427f5"],"page":1,"page_size":100}',
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
                '{"from_message_id":"986714","limit":10,"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5"}',
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
                '{"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5","text":"Test Message"}',
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
                '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
                new \SplFileInfo(__DIR__.'/../../../.php_cs.dist'),
                'test.txt',
            ],
            [
                'POST',
                '/v1/chat/send/file',
                '{
    "base64_content": "PD9waHAKCiRydWxlcyA9IFsKICAgICdAU3ltZm9ueScgICAgICAgICAgICAgICAgICAgICA9PiB0cnVlLAogICAgJ2JsYW5rX2xpbmVfYWZ0ZXJfb3BlbmluZ190YWcnID0+IGZhbHNlLCAvLyA8P3BocCBkZWNsYXJlKHN0cmljdF90eXBlcz0xKTsKICAgICdsaW5lYnJlYWtfYWZ0ZXJfb3BlbmluZ190YWcnICA9PiBmYWxzZSwgLy8gPD9waHAgZGVjbGFyZShzdHJpY3RfdHlwZXM9MSk7CiAgICAnYmluYXJ5X29wZXJhdG9yX3NwYWNlcycgICAgICAgPT4gWwogICAgICAgICdvcGVyYXRvcnMnID0+IFsKICAgICAgICAgICAgJz0+JyA9PiAnYWxpZ24nLAogICAgICAgIF0sCiAgICBdLAogICAgJ2RlY2xhcmVfc3RyaWN0X3R5cGVzJyAgICAgICAgID0+IHRydWUsCl07CgokZmluZGVyID0gUGhwQ3NGaXhlclxGaW5kZXI6OmNyZWF0ZSgpCiAgICAtPmluKF9fRElSX18pOwoKcmV0dXJuIFBocENzRml4ZXJcQ29uZmlnOjpjcmVhdGUoKQogICAgLT5zZXRGaW5kZXIoJGZpbmRlcikKICAgIC0+c2V0Umlza3lBbGxvd2VkKHRydWUpCiAgICAtPnNldFJ1bGVzKCRydWxlcykKICAgIC0+c2V0VXNpbmdDYWNoZSh0cnVlKTsK",
    "chat_id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5",
    "name": ".php_cs.dist"
}',
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
            ['00000000-0000-0'],
            [
                'POST',
                '/v1/chat/start',
                '{"posting_number":"00000000-0000-0"}',
            ],
            '{"result": {"chat_id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5"}}',
            static function ($result): void {
                self::assertEquals('3cdf5407-9f90-4752-8105-8f1d4cd427f5', $result);
            }
        );
    }
}
