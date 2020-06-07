<?php

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class CategoriesServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return CategoriesService::class;
    }

    public function testTree(): void
    {
        $responseJson = <<<JSON
{
  "result": [
    {
      "category_id": 1,
      "title": "Books",
      "children": [
        {
          "category_id": 2,
          "title": "Glossary",
          "children": []
        },
        {
          "category_id": 3,
          "title": "Science Fiction",
          "children": []
        }
      ]
    }
  ]
}
JSON;

        $expectedOptions = [
            'body' => '{"category_id":17036076,"language":"EN"}',
        ];
        $client = $this->createClient('POST', '/v1/category/tree', $expectedOptions, $responseJson);
        /** @var CategoriesService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->tree(17036076, 'EN');
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }

    public function testAttribute(): void
    {
        $responseJson = <<<JSON
{
    "result": {
        "id": 1,
        "name": "Explosive",
        "description": "Mark for product if it is explosive", 
        "type": "bool",
        "is_collection": false,
        "is_required": false,
        "group_id": 0,
        "group_name": "", 
        "dictionary_id": 0 
    }
}
JSON;

        $expectedOptions = [
            'body' => '{"category_id":17036076,"language":"EN","attribute_type":"required"}',
        ];
        $client = $this->createClient('POST', '/v1/category/attribute', $expectedOptions, $responseJson);
        /** @var CategoriesService $svc */
        $svc = $this->createSvc($client);
        $result = $svc->attributes(17036076, 'EN', ['attribute_type' => 'required', 'foo' => 'bar']);
        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }
}
