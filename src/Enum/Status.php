<?php

namespace Gam6itko\OzonSeller\Enum;

final class Status
{
    const AwaitingApprove = 'awaiting_approve';
    const AwaitingPackaging = 'awaiting_packaging';
    const AwaitingDeliver = 'awaiting_deliver';
    const Delivering = 'delivering';
    const Delivered = 'delivered';
    const Cancelled = 'cancelled';

    public static function getList()
    {
        return [
            self::AwaitingApprove,
            self::AwaitingPackaging,
            self::AwaitingDeliver,
            self::Delivering,
            self::Delivered,
            self::Cancelled,
        ];
    }
}
