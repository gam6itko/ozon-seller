<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2;

use Gam6itko\OzonSeller\Service\V2\CategoryService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class CategoryServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return CategoryService::class;
    }

    public function testAttribute(): void
    {
        $this->quickTest(
            'attribute',
            [
                17036076,
                ['attribute_type' => 'required', 'language' => 'EN'],
            ],
            [
                'POST',
                '/v2/category/attribute',
                '{"category_id":17036076,"language":"EN","attribute_type":"required"}',
            ]
        );
    }

    public function testAttributeValues()
    {
        $responseJson = <<<JSON
{
  "result": [
    {
      "id": 115845746,
      "value": "President",
      "picture": "http://cdn1.ozone.ru/multimedia/1029443311.jpg",
      "info": "Продукты питания"
    }
  ],
  "has_next": true
}
JSON;

        $this->quickTest(
            'attributeValues',
            [
                17036076,
                8229,
                ['last_value_id' => 0, 'language' => 'EN', 'limit' => 1],
            ],
            [
                'POST',
                '/v2/category/attribute/values',
                '{"category_id":17036076,"attribute_id":8229,"limit":1,"last_value_id":0,"language":"EN"}',
            ],
            $responseJson,
            function (array $result) {
                self::assertArrayHasKey('result', $result);
                self::assertArrayHasKey('has_next', $result);
            }
        );
    }

    public function testAttributeValueByOption(): void
    {
        $this->quickTest(
            'attributeValueByOption',
            [
                'RU',
                [['attribute_id' => 8229, 'option_id' => 400]],
            ],
            [
                'POST',
                '/v2/category/attribute/value/by-option',
                '{"language":"RU","options":[{"attribute_id":8229,"option_id":400}]}',
            ]
        );
    }
}
