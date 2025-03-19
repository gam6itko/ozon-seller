<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V5\Posting;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class FbsService extends AbstractService
{
    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_FbsPostingProductExemplarCreateOrGet
     *
     * @param string $postingNumber
     */
    public function exemplarCreateOrGet($postingNumber): array
    {
        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', '/v5/fbs/posting/product/exemplar/create-or-get', $body);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_FbsPostingProductExemplarSet
     *
     * @param array $exemplarData
     */
    public function setExemplar($exemplarData): bool
    {
        $exemplarData = ArrayHelper::pick($exemplarData, ['posting_number', 'products']);

        foreach ($exemplarData['products'] as &$product) {
            foreach ($product['exemplars'] as &$exemplar) {
                $exemplar = ArrayHelper::pick($exemplar, ['exemplar_id', 'is_gtd_absent', 'is_rnpt_absent', 'mandatory_mark']);
            }
        }

        return $this->request('POST', '/v5/fbs/posting/product/exemplar/set', $exemplarData);
    }
}
