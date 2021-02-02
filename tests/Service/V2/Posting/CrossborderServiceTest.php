<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class CrossborderServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return CrossborderService::class;
    }

    public function testList(): void
    {
        $this->quickTest(
            'list',
            [
                [
                    'dir'    => SortDirection::ASC,
                    'offset' => 0,
                    'limit'  => 10,
                    'filter' => [
                        'since'  => new \DateTime('2018-11-18T11:27:45.154Z'),
                        'to'     => new \DateTime('2019-11-18T11:27:45.154Z'),
                        'status' => Status::AWAITING_APPROVE,
                    ],
                ],
            ],
            [
                'POST',
                '/v2/posting/crossborder/list',
                '{"filter":{"since":"2018-11-18T11:27:45+00:00","to":"2019-11-18T11:27:45+00:00","status":"awaiting_approve"},"dir":"asc","offset":0,"limit":10}',
            ]
        );
    }

    public function testGet(): void
    {
        $this->quickTest(
            'get',
            ['39268230-0002-3'],
            [
                'POST',
                '/v2/posting/crossborder/get',
                '{"posting_number":"39268230-0002-3"}',
            ]
        );
    }

    public function testUnfulfilledList()
    {
        $this->quickTest(
            'unfulfilledList',
            [
                [
                    'status' => Status::AWAITING_APPROVE,
                    'dir'    => SortDirection::DESC,
                    'offset' => 0,
                    'limit'  => 10,
                    'filter' => [
                        'since'  => new \DateTime('2018-11-18T11:27:45.154Z'),
                        'to'     => new \DateTime('2019-11-18T11:27:45.154Z'),
                        'status' => Status::AWAITING_APPROVE,
                    ],
                ],
            ],
            [
                'POST',
                '/v2/posting/crossborder/unfulfilled/list',
                '{"status":["awaiting_approve"],"dir":"desc","offset":0,"limit":10}',
            ]
        );
    }

    public function testShip(): void
    {
        $this->quickTest(
            'ship',
            [
                '39268230-0002-3',
                'AB123456CD',
                15109877837000,
                [
                    ['quantity' => 2, 'sku' => 100056],
                ],
            ],
            [
                'POST',
                '/v2/posting/crossborder/ship',
                '{"posting_number":"39268230-0002-3","tracking_number":"AB123456CD","shipping_provider_id":15109877837000,"items":[{"quantity":2,"sku":100056}]}',
            ]
        );
    }

    public function testShippingProviders()
    {
        $this->quickTest(
            'shippingProviders',
            [],
            [
                'POST',
                '/v2/posting/crossborder/shipping-provider/list',
                '{}',
            ]
        );
    }

    public function testApprove()
    {
        $this->quickTest(
            'approve',
            ['13009555-0001-1'],
            [
                'POST',
                '/v2/posting/crossborder/approve',
                '{"posting_number":"13009555-0001-1"}',
            ],
            '{"result": true}',
            static function ($result): void {
                self::assertTrue($result);
            }
        );
    }

    public function testCancel()
    {
        $this->quickTest(
            'cancel',
            [
                '39268230-0002-3',
                149123456,
                361,
                'Cancel it!',
            ],
            [
                'POST',
                '/v2/posting/crossborder/cancel',
                '{"posting_number":"39268230-0002-3","sku":149123456,"cancel_reason_id":361,"cancel_reason_message":"Cancel it!"}',
            ],
            '{"result": true}',
            static function ($result): void {
                self::assertTrue($result);
            }
        );
    }

    public function testCancelReasons()
    {
        $this->quickTest(
            'cancelReasons',
            [],
            [
                'POST',
                '/v2/posting/crossborder/cancel-reason/list',
                '{}',
            ]
        );
    }
}
