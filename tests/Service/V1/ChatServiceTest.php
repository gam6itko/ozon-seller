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
        $responseJson = <<<JSON
{
  "result": [
    {
      "id": "6639ec81-616e-480d-82b6-111dec41f674",
      "users": [
        {
          "id": "501",
          "type": "seller"
        },
        {
          "id": "5mzh1lzfuhq4jcs2ufoxpnoa",
          "type": "customer"
        }
      ],
      "last_message_id": "1933333401419385131"
    },
    {
      "id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5",
      "users": [
        {
          "id": "501",
          "type": "seller"
        },
        {
          "id": "31494738",
          "type": "customer"
        }
      ],
      "last_message_id": "1933404740364797568"
    }
  ]
}
JSON;
        $expectedOptions = [
            'body' => '{"chat_id_list":["6639ec81-616e-480d-82b6-111dec41f674","3cdf5407-9f90-4752-8105-8f1d4cd427f5"],"page":1,"page_size":100}',
        ];
        $client = $this->createClient('POST', '/v1/chat/list', $expectedOptions, $responseJson);
        /** @var ChatService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->list([
            'chat_id_list' => [
                '6639ec81-616e-480d-82b6-111dec41f674',
                '3cdf5407-9f90-4752-8105-8f1d4cd427f5',
            ],
            'page'         => 1,
            'page_size'    => 100,
        ]);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testHistory(): void
    {
        $responseJson = <<<JSON
{
  "result": [
    {
      "context": {
        "item": {
          "sku": 0
        },
        "order": {
          "order_number": "123456-0001",
          "postings": [
            {
              "delivery_schema": "fbs",
              "posting_number": "13076543-0001-1",
              "sku_list": [
                149512345
              ]
            }
          ]
        }
      },
      "created_at": "2019-11-25T10:43:06.518Z",
      "file": {
        "url": "http://api-seller.ozon.ru/v1/chat/file/3cdf5407-9f90-4752-8105-8f1d4cd427f563f87e6da3651007ab96185f38772032b3918e31.jpg",
        "mime": "image/jpeg",
        "size": 815313,
        "name": "32679625.jpg"
      },
      "id": "1931356687558511593",
      "text": "hello",
      "type": "file",
      "user": {
        "id": "30735682",
        "type": "customer"
      }
    }
  ]
}
JSON;
        $expectedOptions = [
            'body' => '{"from_message_id":"986714","limit":10,"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5"}',
        ];
        $client = $this->createClient('POST', '/v1/chat/history', $expectedOptions, $responseJson);
        /** @var ChatService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->history('3cdf5407-9f90-4752-8105-8f1d4cd427f5', [
            'from_message_id' => '986714',
            'limit'           => 10,
            'foo'             => 'bar',
        ]);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testSendMessage(): void
    {
        $responseJson = <<<JSON
{
  "result": "success"
}
JSON;
        $expectedOptions = [
            'body' => '{"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5","text":"Test Message"}',
        ];
        $client = $this->createClient('POST', '/v1/chat/send/message', $expectedOptions, $responseJson);
        /** @var ChatService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->sendMessage('3cdf5407-9f90-4752-8105-8f1d4cd427f5', 'Test Message');
        self::assertTrue($result);
    }

    public function testSendFile(): void
    {
        $responseJson = <<<JSON
{
  "result": "success"
}
JSON;
        $expectedOptions = [
            'body' => '{"chat_id":"3cdf5407-9f90-4752-8105-8f1d4cd427f5","base64_content":"MSwgMiwgMwo=","name":"test.txt"}',
        ];
        $client = $this->createClient('POST', '/v1/chat/send/file', $expectedOptions, $responseJson);
        /** @var ChatService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->sendFile('MSwgMiwgMwo=', '3cdf5407-9f90-4752-8105-8f1d4cd427f5', 'test.txt');
        self::assertTrue($result);
    }

    public function testStart(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "chat_id": "3cdf5407-9f90-4752-8105-8f1d4cd427f5"
  }
}
JSON;
        $expectedOptions = [
            'body' => '{"order_id":598586936}',
        ];
        $client = $this->createClient('POST', '/v1/chat/start', $expectedOptions, $responseJson);
        /** @var ChatService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->start(598586936);
        self::assertEquals('3cdf5407-9f90-4752-8105-8f1d4cd427f5', $result);
    }
}
