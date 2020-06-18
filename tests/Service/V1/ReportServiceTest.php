<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Enum\TransactionType;
use Gam6itko\OzonSeller\Service\V1\ReportService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class ReportServiceTest extends AbstractTestCase
{
    public function getClass(): string
    {
        return ReportService::class;
    }

    public function testList(): void
    {
        $query = [
            'page'        => 1,
            'page_size'   => 100,
            'report_type' => 'SELLER_TRANSACTIONS',
        ];
        $this->quickTest(
            'list',
            [$query],
            [
                'POST',
                '/v1/report/list',
                '{"page":1,"page_size":100,"report_type":"SELLER_TRANSACTIONS"}',
            ]
        );
    }

    public function testCode(): void
    {
        $this->quickTest(
            'info',
            ['63d60fd4-1959-4087-89fa-2afa320eb2fb'],
            [
                'POST',
                '/v1/report/info',
                '{"code":"63d60fd4-1959-4087-89fa-2afa320eb2fb"}',
            ]
        );
    }

    public function testProducts(): void
    {
        $query = [
            'offer_id'   => ['GJ5O52T5'],
            'search'     => 'SAMSUNG',
            'sku'        => [555929582],
            'visibility' => 'VISIBLE',
        ];

        $this->quickTest(
            'products',
            [$query],
            [
                'POST',
                '/v1/report/products/create',
                '{"offer_id":["GJ5O52T5"],"search":"SAMSUNG","sku":[555929582],"visibility":"VISIBLE"}',
            ]
        );
    }

    public function testTransaction(): void
    {
        $this->quickTest(
            'transaction',
            [new \DateTime('2019-01-01'), new \DateTime('2019-01-15'), 'MEIZU', TransactionType::ALL],
            [
                'POST',
                '/v1/report/transactions/create',
                '{"date_from":"2019-01-01","date_to":"2019-01-15","search":"MEIZU","transaction_type":"ALL"}',
            ]
        );
    }
}
