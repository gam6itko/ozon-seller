<?php declare(strict_types=1);

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
        $this->quickTest(
            'tree',
            [17036076, 'EN'],
            [
                'POST',
                '/v1/category/tree',
                '{"category_id":17036076,"language":"EN"}',
            ]
        );
    }

    public function testAttribute(): void
    {
        $this->quickTest(
            'attributes',
            [
                17036076,
                'EN',
                ['attribute_type' => 'required', 'foo' => 'bar'],
            ],
            [
                'POST',
                '/v1/category/attribute',
                '{"category_id":17036076,"language":"EN","attribute_type":"required"}',
            ]
        );
    }
}
