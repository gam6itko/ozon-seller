<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;

class FbsService extends AbstractService
{
    private $path = '/v3/posting/fbs';

    public function get(string $postingNumber, array $with = []): array
    {
        $body = [
            'posting_number' => $postingNumber,
            'with'           => $this->withDefaults($with),
        ];

        return $this->request('POST', "{$this->path}/get", $body);
    }

    public function list(array $with = [], array $filter = [], string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10): array
    {
        $filter = $this->faceControl($filter, [
            'delivery_method_id',
            'order_id',
            'provider_id',
            'since',
            'status',
            'to',
            'warehouse_id',
        ]);

        $body = [
            'with'   => $this->withDefaults($with),
            'filter' => $filter,
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", $body);
    }

    public function unfulfilledList(array $with, array $filter = [], string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10): array
    {
        $filter = $this->faceControl($filter, [
            'cutoff_from',
            'cutoff_to',
            'delivering_date_from',
            'delivering_date_to',
            'delivery_method_id',
            'provider_id',
            'status',
            'warehouse_id',
        ]);

        $body = [
            'with'   => $this->withDefaults($with),
            'filter' => $filter,
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/unfulfilled/list", $body);
    }

    private function withDefaults(array $with): array
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
