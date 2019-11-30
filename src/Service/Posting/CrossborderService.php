<?php

namespace Gam6itko\OzonSeller\Service\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;

class CrossborderService extends AbstractService
{
    private $path = '/v2/posting/crossborder';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_list
     */
    public function list(\DateTimeInterface $since, \DateTimeInterface $to, string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10): array
    {
        $body = [
            'filter' => [
                'since' => $since->format(DATE_RFC3339),
                'to'    => $to->format(DATE_RFC3339),
            ],
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_get
     */
    public function get(string $postingNumber): array
    {
        return $this->request('POST', "{$this->path}/get", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_unfulfilled_list
     *
     * @return array|string
     *
     * @todo fix {"error":{"code":"BAD_REQUEST","message":"Invalid request payload","data":[{"name":"status","code":"TOO_FEW_ELEMENTS","value":"[]","message":""}]}}
     */
    public function unfulfilledList(string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10): array
    {
        $body = [
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/unfulfilled/list", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_approve
     */
    public function approve(string $postingNumber): bool
    {
        $result = $this->request('POST', "{$this->path}/approve", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);

        return 'true' === $result;
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_cancel
     *
     * @param array|string $sku
     */
    public function cancel(string $postingNumber, $sku, int $cancelReasonId, string $cancelReasonMessage = null): bool
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
        $result = $this->request('POST', "{$this->path}/cancel", ['body' => \GuzzleHttp\json_encode($body)]);

        return 'true' === $result;
    }

    public function cancelReasons(): array
    {
        return $this->request('POST', "{$this->path}/cancel-reason/list", ['body' => '{}']);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_ship
     *
     * @return string list of postings IDs
     */
    public function ship(string $postingNumber, string $track, int $shippingProviderId, array $items): string
    {
        foreach ($items as &$item) {
            $item = $this->faceControl($item, ['quantity', 'sku']);
        }

        $body = [
            'posting_number'       => $postingNumber,
            'tracking_number'      => $track,
            'shipping_provider_id' => $shippingProviderId,
            'items'                => $items,
        ];

        return $this->request('POST', "{$this->path}/ship", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-cb_shipping_provider_list
     */
    public function shippingProviders(): array
    {
        return $this->request('POST', "{$this->path}/shipping-provider/list", ['body' => '{}']);
    }
}
