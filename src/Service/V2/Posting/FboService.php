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
    public function list(string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10, array $filter = []): array
    {
        $filter = $this->faceControl($filter, ['since', 'to', 'status']);
        foreach (['since', 'to'] as $key) {
            if (isset($filter[$key]) && $filter[$key] instanceof \DateTimeInterface) {
                $filter[$key] = $filter[$key]->format(DATE_RFC3339);
            }
        }

        $body = [
            'filter' => $filter,
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_get
     */
    public function get(string $postingNumber): array
    {
        return $this->request('POST', "{$this->path}/get", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);
    }
}
