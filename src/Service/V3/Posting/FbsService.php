<?php

declare(strict_types=1);

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

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_GetFbsPostingList
     */
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
            'status',
            'since',
            'to',
            'warehouse_id',
        ]);

        //default filter parameters
        $requestData['filter'] = array_merge(
            [
                'since' => (new \DateTime('now - 7 days'))->format(DATE_W3C),
                'to'    => (new \DateTime('now'))->format(DATE_W3C),
            ],
            $requestData['filter']
        );

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

        //https://github.com/gam6itko/ozon-seller/issues/48
        if (
            (empty($requestData['filter']['cutoff_from']) && empty($requestData['filter']['cutoff_to'])) &&
            (empty($requestData['filter']['delivering_date_from']) && empty($requestData['filter']['delivering_date_to']))
        ) {
            throw new \LogicException('Not defined mandatory filter date ranges `cutoff` or `delivering_date`');
        }

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
