<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V5;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @psalm-type TInfoPricesRequestFilter = array{
 *      offer_id?: list<string>,
 *      product_id?: list<string>,
 *      visibility?: string,
 * }
 * @psalm-type TInfoPricesResponse = array{
 *      items: list<array<string,mixed>>,
 *      cursor: string,
 *      total: int
 * }
 */
class ProductService extends AbstractService
{
    private $path = '/v5/product';

    /**
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_GetProductInfoPrices
     *
     * @param TInfoPricesRequestFilter $filter
     *
     * @return TInfoPricesResponse
     */
    public function infoPrices(array $filter, string $cursor = '', int $limit = 100)
    {
        assert($limit > 0 && $limit <= 1000);

        $body = [
            'filter' => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']) ?: new \stdClass(),
            'cursor' => $cursor,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/info/prices", $body);
    }
}
