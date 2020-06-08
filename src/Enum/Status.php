<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class Status
{
    const AWAITING_APPROVE = 'awaiting_approve';
    const AWAITING_PACKAGING = 'awaiting_packaging';
    const AWAITING_DELIVER = 'awaiting_deliver';
    const DELIVERING = 'delivering';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';

    public static function getList()
    {
        return [
            self::AWAITING_APPROVE,
            self::AWAITING_PACKAGING,
            self::AWAITING_DELIVER,
            self::DELIVERING,
            self::DELIVERED,
            self::CANCELLED,
        ];
    }
}
