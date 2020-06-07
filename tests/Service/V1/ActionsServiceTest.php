<?php

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\ActionsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class ActionsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ActionsService::class;
    }

    public function testList(): void
    {
        $responseJson = <<<JSON
{
  "result": [
    {
      "action_id": 2328,
      "title": "акция пагинация",
      "date_start": "2020-01-21T13:30:06Z",
      "date_end": "2020-01-30T21:00:00Z",
      "potential_products_count": 3,
      "is_participating": false,
      "participating_products_count": 0,
      "action_type": "DISCOUNT",
      "banned_products_count": 1,
      "with_targeting": false
    }
  ]
}
JSON;

        $client = $this->createClient('GET', '/v1/actions', [], $responseJson);
        $svc = $this->createSvc($client);
        $result = $svc->list();
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testCandidates(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "products": [
      {
        "product_id": 15323889,
        "price": 1100,
        "action_price": 1100,
        "max_action_price": 1100
      }
    ],
    "total": 1
  }
}
JSON;
        $expectedOptions = [
            'body' => '{"action_id":2422,"offset":0,"limit":1}',
        ];
        $client = $this->createClient('POST', '/v1/actions/candidates', $expectedOptions, $responseJson);
        $svc = $this->createSvc($client);
        $result = $svc->candidates(2422, 0, 1);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testProducts(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "products": [
      {
        "product_id": 15323889,
        "price": 1100,
        "action_price": 1100,
        "max_action_price": 1100
      }
    ],
    "total": 1
  }
}
JSON;
        $expectedOptions = [
            'body' => '{"action_id":2422,"offset":0,"limit":1}',
        ];
        $client = $this->createClient('POST', '/v1/actions/products', $expectedOptions, $responseJson);
        $svc = $this->createSvc($client);
        $result = $svc->products(2422, 0, 1);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testProductsActivate(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "product_ids": [
      15323889
    ]
  }
}
JSON;
        $expectedOptions = [
            'body' => '{"action_id":2422,"products":[{"product_id":15323889,"action_price":931}]}',
        ];
        $client = $this->createClient('POST', '/v1/actions/products/activate', $expectedOptions, $responseJson);
        $svc = $this->createSvc($client);
        $result = $svc->productsActivate(2422, [
            'product_id'   => 15323889,
            'action_price' => 931.00,
        ]);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testProductsDeactivate(): void
    {
        $responseJson = <<<JSON
{
  "result": {
    "product_ids": [
      15323889
    ]
  }
}
JSON;
        $expectedOptions = [
            'body' => '{"action_id":2422,"product_ids":[15323889]}',
        ];
        $client = $this->createClient('POST', '/v1/actions/products/deactivate', $expectedOptions, $responseJson);
        $svc = $this->createSvc($client);
        $result = $svc->productsDeactivate(2422, [15323889]);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }
}
