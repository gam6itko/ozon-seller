<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1\Posting;

use Gam6itko\OzonSeller\Service\AbstractService;

/**
 * @psalm-type TCancelReasonData = array{
 *     id: int,
 *     title: string,
 *     type_id: string
 * }
 * @psalm-type TCancelReasonResponseData = array{
 *     posting_number: string,
 *     reasons: TCancelReasonData[]
 * }
 */
class FbsService extends AbstractService
{
    private $path = '/v1/posting/fbs';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_GetLabelBatch
     */
    public function packageLabelGet(int $taskId): array
    {
        $body = [
            'task_id' => $taskId,
        ];

        return $this->request('POST', "{$this->path}/package-label/get", $body);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_GetPostingFbsCancelReasonV1
     *
     * @param string[] $postingNumbers shipment numbers
     *
     * @return TCancelReasonResponseData[]
     */
    public function cancelReason(array $postingNumbers): array
    {
        if (empty($postingNumbers)) {
            throw new \InvalidArgumentException('Empty posting list');
        }

        $body = [
            'related_posting_numbers' => $postingNumbers,
        ];

        return $this->request('POST', "{$this->path}/cancel-reason", $body);
    }
}
