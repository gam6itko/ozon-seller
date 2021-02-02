<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;

class FboService extends AbstractService
{
    private $path = '/v2/posting/fbo';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_list
     *
     * @param array $filter [since, to, status]
     */
    public function list($sort = SortDirection::ASC, int $offset = 0, int $limit = 10, array $filter = []): array
    {
        if (is_array($sort)) {
            $requestData = $sort;
            @trigger_error('You should pass array as first argument');
        } else {
            $requestData = [
                'filter' => $filter,
                'dir'    => $sort,
                'offset' => $offset,
                'limit'  => $limit,
            ];
        }

        $default = [
            'filter' => [],
            'dir'    => SortDirection::ASC,
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            $this->faceControl($requestData, array_keys($default))
        );

        $filter = $this->faceControl($requestData['filter'], ['since', 'to', 'status']);
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
    public function get(string $postingNumber): array
    {
        return $this->request('POST', "{$this->path}/get", ['posting_number' => $postingNumber]);
    }
}
