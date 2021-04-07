<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Service\GetOrderInterface;
use Gam6itko\OzonSeller\Service\HasOrdersInterface;
use Gam6itko\OzonSeller\Service\HasUnfulfilledOrdersInterface;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class CrossborderService extends AbstractService implements HasOrdersInterface, HasUnfulfilledOrdersInterface, GetOrderInterface
{
    private $path = '/v2/posting/crossborder';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_list
     */
    public function list(array $requestData = []): array
    {
        $default = [
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        $filter = ArrayHelper::pick($requestData['filter'], ['since', 'to', 'status']);
        foreach (['since', 'to'] as $key) {
            if (isset($filter[$key]) && $filter[$key] instanceof \DateTimeInterface) {
                $filter[$key] = $filter[$key]->format(DATE_RFC3339);
            }
        }
        $requestData['filter'] = $filter;

        return $this->request('POST', "{$this->path}/list", $requestData);
    }

    /**
     * @see  https://cb-api.ozonru.me/apiref/en/#t-cb_unfulfilled_list
     *
     * @return array|string
     *
     * @todo fix {"error":{"code":"BAD_REQUEST","message":"Invalid request payload","data":[{"name":"status","code":"TOO_FEW_ELEMENTS","value":"[]","message":""}]}}
     */
    public function unfulfilledList(array $requestData = []): array
    {
        $default = [
            'status' => Status::getList(),
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        if (is_string($requestData['status'])) {
            $requestData['status'] = [$requestData['status']];
        }

        return $this->request('POST', "{$this->path}/unfulfilled/list", $requestData);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_get
     */
    public function get(string $postingNumber, array $options = []): array
    {
        return $this->request('POST', "{$this->path}/get", ['posting_number' => $postingNumber]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_approve
     */
    public function approve(string $postingNumber): bool
    {
        return $this->request('POST', "{$this->path}/approve", ['posting_number' => $postingNumber]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_cancel
     *
     * @param array|string $sku
     */
    public function cancel(string $postingNumber, $sku, int $cancelReasonId, string $cancelReasonMessage = ''): bool
    {
        if (is_string($sku)) {
            $sku = [$sku];
        }
        $body = [
            'posting_number'        => $postingNumber,
            'sku'                   => $sku,
            'cancel_reason_id'      => $cancelReasonId,
            'cancel_reason_message' => $cancelReasonMessage,
        ];

        return $this->request('POST', "{$this->path}/cancel", $body);
    }

    public function cancelReasons(): array
    {
        return $this->request('POST', "{$this->path}/cancel-reason/list", '{}');
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_ship
     *
     * @return string list of postings IDs
     */
    public function ship(string $postingNumber, string $track, int $shippingProviderId, array $items): array
    {
        foreach ($items as &$item) {
            $item = ArrayHelper::pick($item, ['quantity', 'sku']);
        }

        $body = [
            'posting_number'       => $postingNumber,
            'tracking_number'      => $track,
            'shipping_provider_id' => $shippingProviderId,
            'items'                => $items,
        ];

        return $this->request('POST', "{$this->path}/ship", $body);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_shipping_provider_list
     */
    public function shippingProviders(): array
    {
        return $this->request('POST', "{$this->path}/shipping-provider/list", '{}');
    }
}
