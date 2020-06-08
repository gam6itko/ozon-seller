<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\V2\Posting\FboService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class FboServiceTests extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FboService::class;
    }

    public function testList()
    {
        $this->quickTest(
            'list',
            [
                SortDirection::ASC,
                0,
                10,
                [
                    'since'  => new \DateTime('2018-11-18T11:27:45.154Z'),
                    'to'     => new \DateTime('2019-11-18T11:27:45.154Z'),
                    'status' => Status::AWAITING_APPROVE,
                ],
            ],
            [
                'POST',
                '/v2/posting/fbo/list',
                ['body' => '{"filter":{"since":"2018-11-18T11:27:45+00:00","to":"2019-11-18T11:27:45+00:00","status":"awaiting_approve"},"dir":"asc","offset":0,"limit":10}'],
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
                '/v2/posting/fbo/get',
                ['body' => '{"posting_number":"39268230-0002-3"}'],
            ]
        );
    }
}
