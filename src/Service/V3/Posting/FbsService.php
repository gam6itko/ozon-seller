<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Service\HasOrdersInterface;
use Gam6itko\OzonSeller\Service\HasUnfulfilledOrdersInterface;

class FbsService extends AbstractService implements HasOrdersInterface, HasUnfulfilledOrdersInterface
{
    private $path = '/v3/posting/fbs';

    public function list(array $requestData = []): array
    {
        $default = [
            'with'   => $this->withDefaults(),
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            $this->faceControl($requestData, array_keys($default))
        );

        $requestData['filter'] = $this->faceControl($requestData['filter'], [
            'delivery_method_id',
            'order_id',
            'provider_id',
            'since',
            'status',
            'to',
            'warehouse_id',
        ]);

        return $this->request('POST', "{$this->path}/list", $requestData);
    }

    public function unfulfilledList(array $requestData = []): array
    {
        $default = [
            'with'   => $this->withDefaults(),
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            $this->faceControl($requestData, array_keys($default))
        );

        $requestData['filters'] = $this->faceControl($requestData['filters'], [
            'cutoff_from',
            'cutoff_to',
            'delivering_date_from',
            'delivering_date_to',
            'delivery_method_id',
            'provider_id',
            'status',
            'warehouse_id',
        ]);

        return $this->request('POST', "{$this->path}/unfulfilled/list", $requestData);
    }

    public function get(string $postingNumber, array $with = []): array
    {
        $body = [
            'posting_number' => $postingNumber,
            'with'           => $this->withDefaults($with),
        ];

        return $this->request('POST', "{$this->path}/get", $body);
    }

    private function withDefaults(array $with = []): array
    {
        //with defaults
        $withKeys = ['analytics_data', 'barcodes', 'financial_data'];
        $with = $this->faceControl($with, $withKeys);

        return array_merge(
            array_combine($withKeys, array_pad([], 3, true)),
            $with
        );
    }
}
