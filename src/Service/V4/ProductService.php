<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V4;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class ProductService extends AbstractService
{
    private $path = '/v4/product';

    /**
     * @see https://api-seller.ozon.ru/v4/product/info/prices
     */
    public function infoPrices(array $filter, string $lastId = '', int $limit = 100)
    {
        assert($limit > 0 && $limit <= 1000);

        $body = [
            'filter' => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']) ?: new \stdClass(),
            'last_id' => $lastId ?? '',
            'limit' => $limit
        ];

        return $this->request('POST', "{$this->path}/info/prices", $body);
    }
}
