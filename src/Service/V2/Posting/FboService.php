<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Service\GetOrderInterface;
use Gam6itko\OzonSeller\Service\HasOrdersInterface;
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
 *     with?: array
 * }
 */
class FboService extends AbstractService implements HasOrdersInterface, GetOrderInterface
{
    private $path = '/v2/posting/fbo';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_list
     *
     * @param TListRequestData|array<array-key, mixed> $requestData
     */
    public function list(array $requestData = []): array
    {
        $default = [
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
            'with'   => WithResolver::getDefaults(2, PostingScheme::FBO),
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
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_get
     */
    public function get(string $postingNumber, array $options = []): array
    {
        return $this->request('POST', "{$this->path}/get", [
            'posting_number' => $postingNumber,
            'with'           => WithResolver::resolve($options, 2, PostingScheme::FBO),
        ]);
    }
}
