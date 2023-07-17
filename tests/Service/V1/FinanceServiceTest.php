<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\FinanceService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class FinanceServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FinanceService::class;
    }

    public function testRealization(): void
    {
        $this->quickTest(
            'realization',
            [
                ['date' => '2022-02'],
            ],
            [
                'POST',
                '/v1/finance/realization',
                '{"date":"2022-02"}',
            ]
        );
    }
}
