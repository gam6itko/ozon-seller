<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1\Posting;

use Gam6itko\OzonSeller\Service\AbstractService;


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
}
