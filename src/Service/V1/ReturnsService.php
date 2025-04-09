<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class ReturnsService extends AbstractService
{
    private $path = '/v1/returns';

    /**
     * Информация о возвратах FBO и FBS
     *
     * @param array  $requestData   ['filter' => array, 'last_id' => int, 'limit' => int]
     */
    public function list(array $requestData): array
    {
        $default = [
            'filter' => [],
            'last_id' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        return $this->request('POST', "{$this->path}/list", $requestData);
    }
}
