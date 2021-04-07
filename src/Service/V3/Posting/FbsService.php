<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3\Posting;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Service\GetOrderInterface;
use Gam6itko\OzonSeller\Service\HasOrdersInterface;
use Gam6itko\OzonSeller\Service\HasUnfulfilledOrdersInterface;
use Gam6itko\OzonSeller\Utils\ArrayHelper;
use Gam6itko\OzonSeller\Utils\WithResolver;

class FbsService extends AbstractService implements HasOrdersInterface, HasUnfulfilledOrdersInterface, GetOrderInterface
{
    private $path = '/v3/posting/fbs';

    public function list(array $requestData = []): array
    {
        $default = [
            'with'   => WithResolver::getDefaults(3, PostingScheme::FBS),
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        $requestData['filter'] = ArrayHelper::pick($requestData['filter'], [
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
            'with'   => WithResolver::getDefaults(3, PostingScheme::FBS),
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        $requestData['filter'] = ArrayHelper::pick($requestData['filter'], [
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

    public function get(string $postingNumber, array $options = []): array
    {
        return $this->request('POST', "{$this->path}/get", [
            'posting_number' => $postingNumber,
            'with'           => WithResolver::resolve($options, 3, PostingScheme::FBS),
        ]);
    }
}
