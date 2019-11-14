<?php

namespace Gam6itko\OzonSeller\Enum;

final class Status
{
    const AWAITING_APPROVE = 'awaiting_approve';
    const AWAITING_PACKAGING = 'awaiting_packaging';
    const AWAITING_DELIVER = 'awaiting_deliver';
    const DELIVERING = 'delivering';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';

    // todo remove in 0.2
    /** @deprecated use AWAITING_APPROVE */
    const AwaitingApprove = 'awaiting_approve';
    /** @deprecated use AWAITING_PACKAGING */
    const AwaitingPackaging = 'awaiting_packaging';
    /** @deprecated use AWAITING_DELIVER */
    const AwaitingDeliver = 'awaiting_deliver';
    /** @deprecated use DELIVERING */
    const Delivering = 'delivering';
    /** @deprecated use DELIVERED */
    const Delivered = 'delivered';
    /** @deprecated use CANCELLED */
    const Cancelled = 'cancelled';

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
