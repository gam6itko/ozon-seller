<?php

namespace Gam6itko\OzonSeller\Enum;

final class Visibility
{
    /** @var string all products */
    const ALL = 'ALL';
    /** @var string products, visible for customers */
    const VISIBLE = 'VISIBLE';
    /** @var string products, invisible for customers for some reason */
    const INVISIBLE = 'INVISIBLE';
    /** @var string products with empty stock */
    const EMPTY_STOCK = 'EMPTY_STOCK';
    /** @var string products with empty stock and state=processed (so you can set stock) */
    const READY_TO_SUPPLY = 'READY_TO_SUPPLY';
    /** @var string products which are failed on some step */
    const STATE_FAILED = 'STATE_FAILED';
}
