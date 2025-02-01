<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\V2\Posting\FbsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\FbsService
 */
class FbsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    public function testList(): void
    {
        $this->quickTest(
            'list',
            [
                [
                    'filter' => [
                        'since'  => new \DateTime('2018-11-18T11:27:45.154Z'),
                        'to'     => new \DateTime('2019-11-18T11:27:45.154Z'),
                        'status' => Status::AWAITING_APPROVE,
                    ],
                ],
            ],
            [
                'POST',
                '/v2/posting/fbs/list',
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
                '/v2/posting/fbs/get',
                '{"posting_number":"39268230-0002-3","with":{"analytics_data":false,"barcodes":false,"financial_data":false}}',
            ]
        );
    }

    /**
     * @covers ::unfulfilledList
     *
     * @dataProvider dataUnfulfilledList
     */
    public function testUnfulfilledList(array $args, string $responseJson): void
    {
        $this->quickTest(
            'unfulfilledList',
            $args,
            [
                'POST',
                '/v2/posting/fbs/unfulfilled/list',
                $responseJson,
            ]
        );
    }

    public function dataUnfulfilledList(): iterable
    {
        yield [
            [
                [
                    'status' => [
                        Status::AWAITING_APPROVE,
                    ],
                    'with'   => ['barcodes' => true],
                ],
            ],
            '{"with":{"barcodes":true},"status":["awaiting_approve"],"sort_by":"updated_at","dir":"asc","offset":0,"limit":10}',
        ];

        yield [
            [
                [
                    'status' => [
                        Status::AWAITING_APPROVE,
                    ],
                ],
            ],
            '{"with":{"barcodes":false},"status":["awaiting_approve"],"sort_by":"updated_at","dir":"asc","offset":0,"limit":10}',
        ];
    }

    public function testShip(): void
    {
        $packages = [
            ['items' => [['quantity' => 3, 'sku' => 123065]]],
        ];
        $this->quickTest(
            'ship',
            [$packages, '13076543-0001-1'],
            [
                'POST',
                '/v2/posting/fbs/ship',
                '{"packages":[{"items":[{"quantity":3,"sku":123065}]}],"posting_number":"13076543-0001-1"}',
            ]
        );
    }

    public function testActCreate(): void
    {
        $this->quickTest(
            'actCreate',
            [
                [
                    'containers_count'   => '1917',
                    'delivery_method_id' => 11,
                ],
            ],
            [
                'POST',
                '/v2/posting/fbs/act/create',
                '{"containers_count":1917,"delivery_method_id":11}',
            ],
            '{"result": { "id": 15684442104000 }}',
            static function ($result) {
                self::assertSame(15684442104000, $result);
            }
        );
    }

    public function testActCheckStatus(): void
    {
        $this->quickTest(
            'actCheckStatus',
            [15684442104000],
            [
                'POST',
                '/v2/posting/fbs/act/check-status',
                '{"id":15684442104000}',
            ]
        );
    }

    public function testActGetPdf(): void
    {
        $this->quickTest(
            'actGetPdf',
            [15684442104000],
            [
                'POST',
                '/v2/posting/fbs/act/get-pdf',
                '{"id":15684442104000}',
            ],
            'pdf_content',
            static function ($string) {
                self::assertSame('pdf_content', $string);
            }
        );
    }

    public function testPackageLabel(): void
    {
        $this->quickTest(
            'packageLabel',
            ['13076543-0001-1'],
            [
                'POST',
                '/v2/posting/fbs/package-label',
                '{"posting_number":["13076543-0001-1"]}',
            ],
            'pdf_content',
            static function ($string) {
                self::assertSame('pdf_content', $string);
            }
        );
    }

    public function testArbitration(): void
    {
        $this->quickTest(
            'arbitration',
            ['13076543-0001-1'],
            [
                'POST',
                '/v2/posting/fbs/arbitration',
                '{"posting_number":["13076543-0001-1"]}',
            ],
            '{"result":"true"}',
            static function ($result) {
                self::assertTrue($result);
            }
        );
    }

    public function testAwaitingDelivery(): void
    {
        $this->quickTest(
            'awaitingDelivery',
            [
                '13076543-0001-1',
            ],
            [
                'POST',
                '/v2/posting/fbs/awaiting-delivery',
                '{"posting_number":["13076543-0001-1"]}',
            ]
        );

        $this->quickTest(
            'awaitingDelivery',
            [
                ['13076543-0001-1', '02898753-0009-2'],
            ],
            [
                'POST',
                '/v2/posting/fbs/awaiting-delivery',
                '{"posting_number":["13076543-0001-1","02898753-0009-2"]}',
            ]
        );
    }

    public function testCancel(): void
    {
        $this->quickTest(
            'cancel',
            [
                '39268230-0002-3',
                361,
                'Cancel it!',
            ],
            [
                'POST',
                '/v2/posting/fbs/cancel',
                '{"posting_number":"39268230-0002-3","cancel_reason_id":361,"cancel_reason_message":"Cancel it!"}',
            ],
            '{"result": "true"}',
            static function ($result): void {
                self::assertTrue($result);
            }
        );
    }

    public function testCancelReasons(): void
    {
        $this->quickTest(
            'cancelReasons',
            [],
            [
                'POST',
                '/v2/posting/fbs/cancel-reason/list',
                '{}',
            ]
        );
    }

    /**
     * @covers ::digitalActGetPdf
     */
    public function testDigitalActGetPdf(): void
    {
        $this->quickTest(
            'digitalActGetPdf',
            [123, 'act_of_acceptance'],
            [
                'POST',
                '/v2/posting/fbs/digital/act/get-pdf',
                \json_encode([
                    'id'       => 123,
                    'doc_type' => 'act_of_acceptance',
                ]),
            ],
            \json_encode([
                'result' => [
                    'header' => [],
                    'rows'   => [],
                ],
            ])
        );
    }

    /**
     * @covers ::productCountryList
     */
    public function testProductCountryList(): void
    {
        $this->quickTest(
            'productCountryList',
            [
                'Китай',
            ],
            [
                'POST',
                '/v2/posting/fbs/product/country/list',
                '{"name_search":"Китай"}',
            ],
            \json_encode([
                [
                    'name'             => 'Китайская Республика',
                    'country_iso_code' => 'TW',
                ],
                [
                    'name'             => 'Китай (Китайская Народная Республика)',
                    'country_iso_code' => 'CN',
                ],
            ]),
            static function ($result): void {
                self::assertCount(2, $result);
                self::assertArrayHasKey('name', $result[0]);
                self::assertArrayHasKey('country_iso_code', $result[0]);
                self::assertEquals('CN', $result[1]['country_iso_code']);
            }
        );
    }

    /**
     * @covers ::productCountrySet
     */
    public function testProductCountrySet(): void
    {
        $this->quickTest(
            'productCountrySet',
            [
                '57195475-0050-3',
                180550365,
                'NO',
            ],
            [
                'POST',
                '/v2/posting/fbs/product/country/set',
                '{"posting_number":"57195475-0050-3","product_id":180550365,"country_iso_code":"NO"}',
            ],
            \json_encode([
                'product_id'    => 180550365,
                'is_gtd_needed' => true,
            ]),
            static function ($result): void {
                self::assertCount(2, $result);
                self::assertArrayHasKey('product_id', $result);
                self::assertArrayHasKey('is_gtd_needed', $result);
                self::assertEquals(180550365, $result['product_id']);
                self::assertTrue($result['is_gtd_needed']);
            }
        );
    }

    /**
     * @covers ::setTrackingNumber
     *
     * @dataProvider dataSetTrackingNumber
     */
    public function testSetTrackingNumber(array $productsFilter, string $expectedJsonString): void
    {
        $this->quickTest(
            'setTrackingNumber',
            $productsFilter,
            [
                'POST',
                '/v2/fbs/posting/tracking-number/set',
                $expectedJsonString,
            ]
        );
    }

    public function dataSetTrackingNumber(): iterable
    {
        $arguments = [
            'trackingNumbers' => [
                [
                    'posting_number'  => '48173252-0033-2',
                    'tracking_number' => '123123123',
                ],
                [
                    'posting_number'  => '48173251-0021-1',
                    'tracking_number' => '3214567-Fa',
                ],
            ],
        ];
        yield [
            $arguments,
            '{"tracking_numbers":[{"posting_number":"48173252-0033-2","tracking_number":"123123123"},{"posting_number":"48173251-0021-1","tracking_number":"3214567-Fa"}]}',
        ];

        $arguments['trackingNumbers'][1]['extra_data'] = 'useless value';
        yield [
            $arguments,
            '{"tracking_numbers":[{"posting_number":"48173252-0033-2","tracking_number":"123123123"},{"posting_number":"48173251-0021-1","tracking_number":"3214567-Fa"}]}',
        ];

        $arguments = [
            'trackingNumbers' => [
                'posting_number'  => '48173252-0033-2',
                'tracking_number' => '123123123',
            ],
        ];
        yield [
            $arguments,
            '{"tracking_numbers":[{"posting_number":"48173252-0033-2","tracking_number":"123123123"}]}',
        ];

    }

}
