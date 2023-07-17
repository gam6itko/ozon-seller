<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V1;

use Gam6itko\OzonSeller\Service\V1\BrandService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class BrandServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return BrandService::class;
    }

    public function testCompanyCertificationList(): void
    {
        $this->quickTest(
            'companyCertificationList',
            [
                ['page_size' => 50],
            ],
            [
                'POST',
                '/v1/brand/company-certification/list',
                '{"page":1,"page_size":50}',
            ]
        );
    }
}
