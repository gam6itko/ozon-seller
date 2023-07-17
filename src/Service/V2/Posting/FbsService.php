<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Service\GetOrderInterface;
use Gam6itko\OzonSeller\Service\HasOrdersInterface;
use Gam6itko\OzonSeller\Service\HasUnfulfilledOrdersInterface;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;
use Gam6itko\OzonSeller\Utils\WithResolver;

/**
 * @psalm-type TListFilter = array{
 *     status?: string,
 *     since?: string|\DateTimeInterface,
 *     to?: string|\DateTimeInterface,
 * }
 * @psalm-type TListRequestData = array{
 *     filter?: TListFilter,
 *     dir: string,
 *     offset?: int,
 *     limit?: int,
 * }
 */
class FbsService extends AbstractService implements HasOrdersInterface, HasUnfulfilledOrdersInterface, GetOrderInterface
{
    private $path = '/v2/posting/fbs';

    /**
     * @param TListRequestData|array<array-key, mixed> $requestData
     *
     * @deprecated use V3\Posting\FbsService::list
     * @see        https://cb-api.ozonru.me/apiref/en/#t-fbs_list
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
     * @deprecated use V3\Posting\FbsService::unfulfilledList
     * @see        https://cb-api.ozonru.me/apiref/en/#t-fbs_unfulfilled_list
     */
    public function unfulfilledList(array $requestData = []): array
    {
        $default = [
            'with'    => WithResolver::resolve($requestData, 2, PostingScheme::FBS, __FUNCTION__),
            'status'  => Status::getList(),
            'sort_by' => 'updated_at',
            'dir'     => SortDirection::ASC,
            'offset'  => 0,
            'limit'   => 10,
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
     * @deprecated use V3\Posting\FbsService::get
     * @see        https://cb-api.ozonru.me/apiref/en/#t-fbs_get
     */
    public function get(string $postingNumber, array $options = []): array
    {
        return $this->request('POST', "{$this->path}/get", [
            'posting_number' => $postingNumber,
            'with'           => WithResolver::resolve($options, 2, PostingScheme::FBS),
        ]);
    }

    /**
     * @return array list of postings IDs
     *
     * @deprecated use V3\Posting\FbsService::ship
     * @see        https://cb-api.ozonru.me/apiref/en/#t-fbs_ship
     */
    public function ship(array $packages, string $postingNumber): array
    {
        foreach ($packages as &$package) {
            $package = ArrayHelper::pick($package, ['items']);
        }

        $body = [
            'packages'       => $packages,
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', "{$this->path}/ship", $body);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_package_label
     *
     * @param array|string $postingNumber
     */
    public function packageLabel($postingNumber): string
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        return $this->request('POST', "{$this->path}/package-label", ['posting_number' => $postingNumber], false);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_arbitration_title
     *
     * @param array|string $postingNumber
     */
    public function arbitration($postingNumber): bool
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $result = $this->request('POST', "{$this->path}/arbitration", ['posting_number' => $postingNumber]);

        return 'true' === $result;
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_cancel_title
     */
    public function cancel(string $postingNumber, int $cancelReasonId, string $cancelReasonMessage = null): bool
    {
        $body = [
            'posting_number'        => $postingNumber,
            'cancel_reason_id'      => $cancelReasonId,
            'cancel_reason_message' => $cancelReasonMessage,
        ];
        $result = $this->request('POST', "{$this->path}/cancel", $body);

        return 'true' === $result;
    }

    public function cancelReasons(): array
    {
        return $this->request('POST', "{$this->path}/cancel-reason/list", '{}'); // todo свериться с исправленной документацией
    }

    /**
     * @param string|array $postingNumber
     *
     * @return array|string
     *
     * @todo return true
     */
    public function awaitingDelivery($postingNumber)
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', "{$this->path}/awaiting-delivery", $body);
    }

    public function getByBarcode(string $barcode): array
    {
        return $this->request('POST', "{$this->path}/get-by-barcode", ['barcode' => $barcode]);
    }

    // <editor-fold desc="/act">

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_PostingFBSActCreate
     *
     * @param array $params [containers_count, delivery_method_id]
     */
    public function actCreate(array $params): int
    {
        $config = [
            'containers_count'   => 'int',
            'delivery_method_id' => 'int',
        ];

        $params = ArrayHelper::pick($params, array_keys($config));
        $params = TypeCaster::castArr($params, $config);
        $result = $this->request('POST', "{$this->path}/act/create", $params);

        return $result['id'];
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-section_postings_fbs_act_check_title
     */
    public function actCheckStatus(int $id): array
    {
        return $this->request('POST', "{$this->path}/act/check-status", ['id' => $id]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-section_postings_fbs_act_get_title
     */
    public function actGetPdf(int $id): string
    {
        return $this->request('POST', "{$this->path}/act/get-pdf", ['id' => $id], false);
    }

    public function actGetContainerLabels(int $id): string
    {
        return $this->request('POST', "{$this->path}/act/get-container-labels", ['id' => $id], false);
    }

    // </editor-fold>

    // <editor-fold desc="/v2/fbs/posting">

    /**
     * @param array|string $postingNumber
     */
    public function delivered($postingNumber): array
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', '/v2/fbs/posting/delivered', $body);
    }

    /**
     * @param array|string $postingNumber
     */
    public function delivering($postingNumber): array
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', '/v2/fbs/posting/delivering', $body);
    }

    /**
     * @param array|string $postingNumber
     */
    public function lastMile($postingNumber): array
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', '/v2/fbs/posting/last-mile', $body);
    }

    public function setTrackingNumber(array $trackingNumbers): array
    {
        if (isset($trackingNumbers['posting_number']) || isset($trackingNumbers['tracking_number'])) {
            $trackingNumbers = [$trackingNumbers];
        }

        foreach ($trackingNumbers as &$tn) {
            $tn = ArrayHelper::pick($tn, ['posting_number', 'tracking_number']);
        }

        $body = [
            'tracking_numbers' => $trackingNumbers,
        ];

        return $this->request('POST', '/v2/fbs/posting/tracking-number/set', $body);
    }

    // </editor-fold>

    /**
     * @param 'act_of_acceptance'|'act_of_mismatch'|'act_of_excess' $docType
     */
    public function digitalActGetPdf(int $id, string $docType): array
    {
        return $this->request('POST', "{$this->path}/digital/act/get-pdf", [
            'id'      => $id,
            'docType' => $docType,
        ]);
    }
}
