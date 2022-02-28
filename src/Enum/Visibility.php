<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class Visibility
{
    /** @var string all products */
    public const ALL = 'ALL';
    /** @var string products, visible for customers */
    public const VISIBLE = 'VISIBLE';
    /** @var string products, invisible for customers for some reason */
    public const INVISIBLE = 'INVISIBLE';
    /** @var string products with empty stock */
    public const EMPTY_STOCK = 'EMPTY_STOCK';
    /** @var string products with empty stock and state=processed (so you can set stock) */
    public const READY_TO_SUPPLY = 'READY_TO_SUPPLY';
    /** @var string products which are failed on some step */
    public const STATE_FAILED = 'STATE_FAILED';
}
