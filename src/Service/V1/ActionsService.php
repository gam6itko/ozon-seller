<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @see    https://cb-api.ozonru.me/apiref/en/#t-title_action
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ActionsService extends AbstractService
{
    private $path = '/v1/actions';

    protected function getDefaultHost(): string
    {
        return 'https://seller-api.ozon.ru/';
    }

    /**
     * Promotional offers list.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_action_available
     */
    public function list(): array
    {
        return $this->request('GET', $this->path);
    }

    /**
     * List of products which can participate in the promotional offer.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_action_available_products
     */
    public function candidates(int $actionId, int $offset = 0, int $limit = 10): array
    {
        $body = [
            'action_id' => $actionId,
            'offset'    => $offset,
            'limit'     => $limit,
        ];

        return $this->request('POST', "{$this->path}/candidates", $body);
    }

    /**
     * List of products which participate in the promotional offer.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_action_products
     */
    public function products(int $actionId, int $offset = 0, int $limit = 10)
    {
        $body = [
            'action_id' => $actionId,
            'offset'    => $offset,
            'limit'     => $limit,
        ];

        return $this->request('POST', "{$this->path}/products", $body);
    }

    /**
     * Add product to the promotional offer.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_action_add_products
     */
    public function productsActivate(int $actionId, array $products): array
    {
        $products = $this->ensureCollection($products);
        foreach ($products as &$p) {
            $p = ArrayHelper::pick($p, ['product_id', 'action_price', 'stock']);
        }
        unset($p);

        $body = [
            'action_id' => $actionId,
            'products'  => $products,
        ];

        return $this->request('POST', "{$this->path}/products/activate", $body);
    }

    /**
     * This method allows to delete products from the promotional offer.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_action_add_products
     */
    public function productsDeactivate(int $actionId, array $productIds): array
    {
        $body = [
            'action_id'   => $actionId,
            'product_ids' => $productIds,
        ];

        return $this->request('POST', "{$this->path}/products/deactivate", $body);
    }
}
