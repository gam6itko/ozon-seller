<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\TypeCaster;

class PassService extends AbstractService
{
    private const CONF = [
        'car_model'            => 'string',
        'car_number'           => 'string',
        'driver_name'          => 'string',
        'driver_patronymic'    => 'string',
        'driver_surname'       => 'string',
        'end_unloading_time'   => 'string',
        'is_regular_pass'      => 'boolean',
        'start_unloading_time' => 'string',
        'telephone'            => 'string',
        'trailer_number'       => 'string',
        'unload_date'          => 'string',
    ];

    public function create(array $data)
    {
        $this->faceControl($data, array_keys(self::CONF));
        TypeCaster::castArr($data, self::CONF);

        return $this->request('POST', '/pass/create', $data, true, false);
    }

    public function getLast()
    {
        return $this->request('POST', '/pass/get/last', '{}', true, false);
    }

    public function update(array $data)
    {
        $this->faceControl($data, array_keys(self::CONF));
        TypeCaster::castArr($data, self::CONF);

        return $this->request('POST', '/pass/update', $data);
    }
}
