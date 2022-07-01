<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V4;

use Gam6itko\OzonSeller\Enum\Visibility;
use Gam6itko\OzonSeller\Service\V2\ProductService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V4\ProductService
 */
class ProductServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ProductService::class;
    }

    /**
     *
     */
    public function testInfoPrices(): void
    {
        $this->quickTest(
            'infoPrices',
            [
                'filter' => [
                    'offer_id' => array(
                        '3244378',
                        '1107890'
                    ),
                    'visibility' => Visibility::ALL
                ],
                'limit' => 100
            ],
            [
                'items' => [
                    [
                        'product_id' => 118495036,
                        'offer_id' => 1107890
                    ],
                    [
                        'product_id' => 296006398,
                        'offer_id' => 3244378
                    ]
                ],
                'total' => 2,
                'last_id' => 'WzI5NjAwNjM5OF0='
            ],
            '{"result":{"items":[{"product_id":118495036,"offer_id":"1107890","price":{"premium_price":"","recommended_price":"","retail_price":"","vat":"0.0","min_ozon_price":"","min_price":"","currency_code":"RUB","auto_action_enabled":false},"price_index":"0.00","marketing_actions":null,"volume_weight":0.3},{"product_id":296006398,"offer_id":"3244378","price":{"old_price":"","premium_price":"","recommended_price":"","retail_price":"","vat":"0.0","min_ozon_price":"","min_price":"","currency_code":"RUB","auto_action_enabled":false},"price_index":"0.00","marketing_actions":null,"volume_weight":0.9}],"total":2,"last_id":"WzI5NjAwNjM5OF0="}}'
        );
    }
}