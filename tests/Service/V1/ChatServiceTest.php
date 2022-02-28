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
                new \SplFileInfo(__DIR__.'/../../../LICENSE'),
                'test.txt',
            ],
            [
                'POST',
                '/v1/chat/send/file',
                '{
    "base64_content": "TUlUIExpY2Vuc2UKCkNvcHlyaWdodCAoYykgMjAyMCBBbGV4YW5kZXIgU3RyaXpoYWsKClBlcm1pc3Npb24gaXMgaGVyZWJ5IGdyYW50ZWQsIGZyZWUgb2YgY2hhcmdlLCB0byBhbnkgcGVyc29uIG9idGFpbmluZyBhIGNvcHkKb2YgdGhpcyBzb2Z0d2FyZSBhbmQgYXNzb2NpYXRlZCBkb2N1bWVudGF0aW9uIGZpbGVzICh0aGUgIlNvZnR3YXJlIiksIHRvIGRlYWwKaW4gdGhlIFNvZnR3YXJlIHdpdGhvdXQgcmVzdHJpY3Rpb24sIGluY2x1ZGluZyB3aXRob3V0IGxpbWl0YXRpb24gdGhlIHJpZ2h0cwp0byB1c2UsIGNvcHksIG1vZGlmeSwgbWVyZ2UsIHB1Ymxpc2gsIGRpc3RyaWJ1dGUsIHN1YmxpY2Vuc2UsIGFuZC9vciBzZWxsCmNvcGllcyBvZiB0aGUgU29mdHdhcmUsIGFuZCB0byBwZXJtaXQgcGVyc29ucyB0byB3aG9tIHRoZSBTb2Z0d2FyZSBpcwpmdXJuaXNoZWQgdG8gZG8gc28sIHN1YmplY3QgdG8gdGhlIGZvbGxvd2luZyBjb25kaXRpb25zOgoKVGhlIGFib3ZlIGNvcHlyaWdodCBub3RpY2UgYW5kIHRoaXMgcGVybWlzc2lvbiBub3RpY2Ugc2hhbGwgYmUgaW5jbHVkZWQgaW4gYWxsCmNvcGllcyBvciBzdWJzdGFudGlhbCBwb3J0aW9ucyBvZiB0aGUgU29mdHdhcmUuCgpUSEUgU09GVFdBUkUgSVMgUFJPVklERUQgIkFTIElTIiwgV0lUSE9VVCBXQVJSQU5UWSBPRiBBTlkgS0lORCwgRVhQUkVTUyBPUgpJTVBMSUVELCBJTkNMVURJTkcgQlVUIE5PVCBMSU1JVEVEIFRPIFRIRSBXQVJSQU5USUVTIE9GIE1FUkNIQU5UQUJJTElUWSwKRklUTkVTUyBGT1IgQSBQQVJUSUNVTEFSIFBVUlBPU0UgQU5EIE5PTklORlJJTkdFTUVOVC4gSU4gTk8gRVZFTlQgU0hBTEwgVEhFCkFVVEhPUlMgT1IgQ09QWVJJR0hUIEhPTERFUlMgQkUgTElBQkxFIEZPUiBBTlkgQ0xBSU0sIERBTUFHRVMgT1IgT1RIRVIKTElBQklMSVRZLCBXSEVUSEVSIElOIEFOIEFDVElPTiBPRiBDT05UUkFDVCwgVE9SVCBPUiBPVEhFUldJU0UsIEFSSVNJTkcgRlJPTSwKT1VUIE9GIE9SIElOIENPTk5FQ1RJT04gV0lUSCBUSEUgU09GVFdBUkUgT1IgVEhFIFVTRSBPUiBPVEhFUiBERUFMSU5HUyBJTiBUSEUKU09GVFdBUkUuCg==",
    "chat_id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5",
    "name": "LICENSE"
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
