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
        $this->quickTest(
            'list',
            [],
            [
                'GET',
                '/v1/actions',
                [],
            ]
        );
    }

    public function testCandidates(): void
    {
        $this->quickTest(
            'candidates',
            [2422, 0, 1],
            [
                'POST',
                '/v1/actions/candidates',
                ['body' => '{"action_id":2422,"offset":0,"limit":1}'],
            ]
        );
    }

    public function testProducts(): void
    {
        $this->quickTest(
            'products',
            [2422, 0, 1],
            [
                'POST',
                '/v1/actions/products',
                ['body' => '{"action_id":2422,"offset":0,"limit":1}'],
            ]
        );
    }

    public function testProductsActivate(): void
    {
        $this->quickTest(
            'productsActivate',
            [
                2422,
                [
                    'product_id'   => 15323889,
                    'action_price' => 931.00,
                ],
            ],
            [
                'POST',
                '/v1/actions/products/activate',
                ['body' => '{"action_id":2422,"products":[{"product_id":15323889,"action_price":931}]}'],
            ]
        );
    }

    public function testProductsDeactivate(): void
    {
        $this->quickTest(
            'productsDeactivate',
            [
                2422,
                [15323889],
            ],
            [
                'POST',
                '/v1/actions/products/deactivate',
                ['body' => '{"action_id":2422,"product_ids":[15323889]}'],
            ]
        );
    }
}
