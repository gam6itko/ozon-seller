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

    public const NOT_MODERATED = 'NOT_MODERATED';
    public const MODERATED = 'MODERATED';
    public const DISABLED = 'DISABLED';
    public const VALIDATION_STATE_PENDING = 'VALIDATION_STATE_PENDING';
    public const VALIDATION_STATE_FAIL = 'VALIDATION_STATE_FAIL';
    public const VALIDATION_STATE_SUCCESS = 'VALIDATION_STATE_SUCCESS';
    public const TO_SUPPLY = 'TO_SUPPLY';
    public const IN_SALE = 'IN_SALE';
    public const REMOVED_FROM_SALE = 'REMOVED_FROM_SALE';
    public const BANNED = 'BANNED';
    public const OVERPRICED = 'OVERPRICED';
    public const CRITICALLY_OVERPRICED = 'CRITICALLY_OVERPRICED';
    public const EMPTY_BARCODE = 'EMPTY_BARCODE';
    public const BARCODE_EXISTS = 'BARCODE_EXISTS';
    public const QUARANTINE = 'QUARANTINE';
    public const ARCHIVED = 'ARCHIVED';
    public const OVERPRICED_WITH_STOCK = 'OVERPRICED_WITH_STOCK';
    public const PARTIAL_APPROVED = 'PARTIAL_APPROVED';
    public const IMAGE_ABSENT = 'IMAGE_ABSENT';
    public const MODERATION_BLOCK = 'MODERATION_BLOCK';
}
