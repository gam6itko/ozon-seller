<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\V2\Posting\FboService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\FboService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @group
 */
class FboServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FboService::class;
    }

    /**
     * @dataProvider dataList
     */
    public function testList(array $arguments, string $json): void
    {
        $this->quickTest(
            'list',
            $arguments,
            [
                'POST',
                '/v2/posting/fbo/list',
                $json,
            ]
        );
    }

    public function dataList(): iterable
    {
        $json = <<<JSON
{
  "filter": {
    "since": "2018-11-18T11:27:45+00:00",
    "to": "2019-11-18T11:27:45+00:00",
    "status": "awaiting_approve"
  },
  "dir": "asc",
  "offset": 0,
  "limit": 10,
  "with": {
    "analytics_data": false,
    "financial_data": false
  }
}
JSON;
        yield [
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
            $json,
        ];

        $json = <<<JSON
{
  "filter": {
    "since": "2018-11-18T11:27:45+00:00",
    "to": "2019-11-18T11:27:45+00:00",
    "status": "awaiting_approve"
  },
  "dir": "desc",
  "offset": 0,
  "limit": 10,
  "with": {
    "analytics_data": true
  }
}
JSON;
        yield [
            [
                [
                    'dir'    => SortDirection::DESC,
                    'offset' => 0,
                    'limit'  => 10,
                    'filter' => [
                        'since'  => new \DateTime('2018-11-18T11:27:45.154Z'),
                        'to'     => new \DateTime('2019-11-18T11:27:45.154Z'),
                        'status' => Status::AWAITING_APPROVE,
                    ],
                    'with'   => [
                        'analytics_data' => true,
                    ],
                ],
            ],
            $json,
        ];
    }

    public function testGet(): void
    {
        $this->quickTest(
            'get',
            ['39268230-0002-3'],
            [
                'POST',
                '/v2/posting/fbo/get',
                '{"posting_number":"39268230-0002-3","with":{"analytics_data":false,"financial_data":false}}',
            ]
        );
    }
}
