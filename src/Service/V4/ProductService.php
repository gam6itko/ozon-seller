<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V4;

use Gam6itko\OzonSeller\Enum\Visibility;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @author David Bakhmach <electroyoustyle@gmail.com>
 */
class ProductService extends AbstractService
{
    private $path = '/v4/product';

    /**
     * Receive products prices info.
     *
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_GetProductInfoPricesV4
     *
     * @param array $requestData - ['filter' => array( 'offer_id' => array(string), 'product_id' => array(int|string), 'visibility' => string ), 'last_id' => string, 'limit' => int]
     *
     * @return array - ['items' => array, 'total' => int, 'last_id' => string]
     */
    public function infoPrices(array $requestData = []) : array
    {
        $default = [
            'filter' => [],
            'last_id' => '',
            'limit'  => 100,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        return $this->request('POST', "{$this->path}/info/prices", $requestData);
    }
}