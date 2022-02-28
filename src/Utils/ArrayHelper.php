<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Utils;

class ArrayHelper
{
    /**
     * Filters unexpected array keys.
     */
    public static function pick(array $query, array $whitelist): array
    {
        return array_intersect_key($query, array_flip($whitelist));
    }
}
