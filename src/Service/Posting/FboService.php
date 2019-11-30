<?php

namespace Gam6itko\OzonSeller\Service\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;

class FboService extends AbstractService
{
    private $path = '/v2/posting/fbo';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_list
     *
     * @return array|string
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
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbo_get
     */
    public function get(string $postingNumber): array
    {
        return $this->request('POST', "{$this->path}/get", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);
    }
}
