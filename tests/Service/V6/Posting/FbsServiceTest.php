<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V6\Posting;

use Gam6itko\OzonSeller\Service\V6\Posting\FbsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

class FbsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    public function testProductExemplarSet()
    {
        $multiBoxQty = 1;
        $postingNumber = '012345678-0004-12';
        $products = [
            'exemplars' => [
                'exemplar_id'    => 0,
                'gtd'            => '12345678/010125/1234567',
                'is_gtd_absent'  => false,
                'is_rnpt_absent' => false,
                'marks'          => [
                ],
                'rnpt'   => 'string',
                'weight' => 0,
            ],
            'has_imei'                   => false,
            'is_gtd_needed'              => true,
            'is_jw_uin_needed'           => false,
            'is_mandatory_mark_needed'   => false,
            'is_mandatory_mark_possible' => false,
            'is_rnpt_needed'             => false,
            'product_id'                 => 1234567,
            'quantity'                   => 1,
            'is_weight_needed'           => false,
            'weight_max'                 => 1.0,
            'weight_min'                 => 0.1,
        ];
        $requestString = json_encode(
            ['multi_box_qty' => $multiBoxQty, 'posting_number' => $postingNumber, 'products' => $products]
        );
        $this->quickTest(
            'productExemplarSet',
            [$multiBoxQty, $postingNumber, $products],
            [
                'POST',
                '/v6/fbs/posting/product/exemplar/set',
                $requestString,
            ]
        );
    }

    public function testProductExemplarCreateOrGet()
    {
        $this->quickTest(
            'productExemplarCreateOrGet',
            ['123456789-0001-1'],
            [
                'POST',
                '/v6/fbs/posting/product/exemplar/create-or-get',
                '{"posting_number":"123456789-0001-1"}',
            ]
        );
    }
}
