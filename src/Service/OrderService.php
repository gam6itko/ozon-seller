<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\Enum\DeliverySchema;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class OrderService extends AbstractService
{
    /**
     * Receive order info.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_order
     *
     * @param int   $orderId
     * @param array $query   ['translit']
     *
     * @return array
     */
    public function info(int $orderId, array $query = [])
    {
        $query = $this->faceControl($query, ['translit']);

        return $this->request('GET', "/v1/order/{$orderId}", ['query' => $query]);
    }

    /**
     * Receive the list of orders.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_list_all
     *
     * @param \DateTimeInterface $since
     * @param \DateTimeInterface $to
     * @param string             $deliverySchema
     * @param array              $query          ['page', 'page_size', 'statuses']
     *
     * @return array
     */
    public function list(\DateTimeInterface $since, \DateTimeInterface $to, string $deliverySchema = DeliverySchema::CROSSBOARDER, array $query = []): array
    {
        $query = $this->faceControl($query, ['page', 'page_size', 'statuses']);

        $arr = array_merge([
            'since'           => $since->format(DATE_RFC3339),
            'to'              => $to->format(DATE_RFC3339),
            'delivery_schema' => $deliverySchema,
        ], $query);

        return $this->request('POST', '/v1/order/list', ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Approve items in the order. Changes approval status for specific items in the order.
     *
     * @param int $orderId Order ID
     *
     * @return bool
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_crossborder
     */
    public function approve(int $orderId)
    {
        $arr = [
            'order_id' => $orderId,
        ];

        return $this->request('POST', '/v1/order/approve/crossborder', ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Receive the list of available shipping providers. Only for Crossborder.
     *
     * @return array
     */
    public function shippingProviders()
    {
        return $this->request('POST', '/v1/order/shipping-provider/list');
    }

    /**
     * @deprecated use shipCrossboader
     */
    public function shipCrossboarder(int $orderId, string $track, int $shippingProviderId, array $items)
    {
        @trigger_error(sprintf('You use method with typo in name. Use shipCrossboader method please'), E_USER_DEPRECATED);

        return $this->shipCrossborder($orderId, $track, $shippingProviderId, $items);
    }

    /**
     * Create a package, mark it as dispatched and provide a tracking number.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_ship_cb
     *
     * @param int    $orderId            Order ID
     * @param string $track              Shipment tracking number
     * @param int    $shippingProviderId Shipping company (provider) ID
     * @param array  $items              Order items array [['item_id', 'quantity']]
     *
     * @return array
     */
    public function shipCrossborder(int $orderId, string $track, int $shippingProviderId, array $items)
    {
        $query = [
            'order_id'             => $orderId,
            'tracking_number'      => $track,
            'shipping_provider_id' => $shippingProviderId,
            'items'                => $items,
        ];

        return $this->request('POST', '/v1/order/ship/crossborder', ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Returns an FBS package label for one or several orders.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_post_order_ship_fbs
     *
     * @param int[] $orderIds
     *
     * @return array
     */
    public function packageLabelFbs(array $orderIds)
    {
        $query = ['order_ids' => $orderIds];

        return $this->request('POST', '/v1/order/package-label/fbs', ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Retreives bill of lading for an FBS orders batch. When using this handle an OZON courier shipment will be created.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_post_order_acceptancedocs_fbs
     *
     * @return array
     */
    public function acceptanceDocFbs()
    {
        return $this->request('POST', '/v1/order/acceptance-doc/fbs', ['body' => '{}']);
    }

    /**
     * Create a package, mark it as dispatched and provide a tracking number.  Only for Fulfilled by Seller (FBS).
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_ship_fbs
     *
     * @param int $orderId  Order ID
     * @param int $packages [{"items": [{"item_id": 123, "quantity": 1}]}]
     *
     * @return array|string
     *
     * @throws \Exception
     */
    public function shipFbs(int $orderId, array $packages)
    {
        $query = [
            'order_id' => $orderId,
            'packages' => $packages,
        ];

        return $this->request('POST', '/v1/order/ship/fbs', ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Will cancel an item in the order, requires cancellation reason to be provided.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_item_crossborder
     *
     * @param int   $orderId    Order ID
     * @param int   $reasonCode Cancellation reason code
     * @param array $itemsIds   List of unique item IDs in the order
     *
     * @return bool
     */
    public function itemsCancelCrossboarder(int $orderId, int $reasonCode, array $itemsIds): bool
    {
        $query = [
            'order_id'    => $orderId,
            'reason_code' => $reasonCode,
            'item_ids'    => $itemsIds,
        ];

        $response = $this->request('POST', '/v1/order/items/cancel/crossborder', ['body' => \GuzzleHttp\json_encode($query)]);

        return 'success' === $response;
    }

    /**
     * Will cancel an order, requires cancellation reason to be provided.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_order_item_fbs
     *
     * @param int $orderId    Order ID
     * @param int $reasonCode Cancellation reason code
     *
     * @return bool
     */
    public function itemsCancelFbs(int $orderId, int $reasonCode): bool
    {
        $query = [
            'order_id'         => $orderId,
            'cancel_reason_id' => $reasonCode,
        ];

        $response = $this->request('POST', '/v1/order/cancel/fbs', ['body' => \GuzzleHttp\json_encode($query)]);

        return 'success' === $response;
    }

    /**
     * Receive the list of unfulfilled orders, within the specified order date and time.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_order_unfulfilled
     *
     * @param array $query ['page', 'page_size']
     *
     * @return bool
     */
    public function unfulfilled(array $query = [])
    {
        $query = $this->faceControl($query, ['page', 'page_size']);

        return $this->request('POST', '/v1/order/unfulfilled', ['query' => $query]);
    }

    /**
     * @return array
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_order_canceled
     */
    public function itemsCancelReasons()
    {
        return $this->request('POST', '/v1/order/cancel-reason/list', ['body' => '{}']);
    }
}
