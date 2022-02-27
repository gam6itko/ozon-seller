<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V3\Posting;

use Gam6itko\OzonSeller\Service\V3\Posting\FbsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V3\Posting\FbsService
 */
class FbsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    /**
     * @covers ::list
     */
    public function testList(): void
    {
        $this->quickTest(
            'list',
            [
                [
                    'filter' => [
                        'since' => '2021-08-01T00:00:00+00:00',
                        'to'    => '2021-08-08T00:00:00+00:00',
                    ],
                ],
            ],
            [
                'POST',
                '/v3/posting/fbs/list',
                '{"with":{"analytics_data":false,"barcodes":false,"financial_data":false},"filter":{"since":"2021-08-01T00:00:00+00:00","to":"2021-08-08T00:00:00+00:00"},"dir":"asc","offset":0,"limit":10}',
            ]
        );
    }

    /**
     * @covers ::unfulfilledList
     */
    public function testUnfulfilledList(): void
    {
        $this->quickTest(
            'unfulfilledList',
            [
                [
                    'filter' => [
                        'cutoff_from' => '2021-11-12T00:00:00Z',
                        'cutoff_to'   => '2021-11-13T00:00:22Z',
                    ],
                ],
            ],
            [
                'POST',
                '/v3/posting/fbs/unfulfilled/list',
                '{"with":{"analytics_data":false,"barcodes":false,"financial_data":false},"dir":"asc","filter":{"cutoff_from": "2021-11-12T00:00:00Z","cutoff_to": "2021-11-13T00:00:22Z"},"offset":0,"limit":10}',
            ]
        );
    }

    public function testUnfulfilledListNoMandatoryFilter(): void
    {
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('Not defined mandatory filter date ranges `cutoff` or `delivering_date`');

        $svc = new FbsService([1,1], $this->createMock(Psr18Client::class), $this->createRequestFactory(), $this->createStreamFactory());
        $svc->unfulfilledList();
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $this->quickTest(
            'get',
            [
                '00000001-00001-1',
                [
                    'analytics_data' => true,
                    'barcodes'       => true,
                    'financial_data' => true,
                ],
            ],
            [
                'POST',
                '/v3/posting/fbs/get',
                '{"posting_number":"00000001-00001-1","with":{"analytics_data":true,"barcodes":true,"financial_data":true}}',
            ]
        );
    }
}
