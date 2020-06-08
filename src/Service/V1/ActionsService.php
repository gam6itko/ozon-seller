<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;

/**
 * @see    https://cb-api.ozonru.me/apiref/en/#t-title_action
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ActionsService extends AbstractService
{
    private $path = '/v1/actions';

    public function __construct(int $clientId, string $apiKey, string $host = 'https://seller-api.ozon.ru/')
    {
        parent::__construct($clientId, $apiKey, $host);
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

        return $this->request('POST', "{$this->path}/candidates", ['body' => \GuzzleHttp\json_encode($body)]);
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

        return $this->request('POST', "{$this->path}/products", ['body' => \GuzzleHttp\json_encode($body)]);
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
            $p = $this->faceControl($p, ['product_id', 'action_price']);
        }
        unset($p);

        $body = [
            'action_id' => $actionId,
            'products'  => $products,
        ];

        return $this->request('POST', "{$this->path}/products/activate", ['body' => \GuzzleHttp\json_encode($body)]);
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

        return $this->request('POST', "{$this->path}/products/deactivate", ['body' => \GuzzleHttp\json_encode($body)]);
    }
}
