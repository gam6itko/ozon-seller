<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Enum;

use Gam6itko\OzonSeller\Enum\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test(): void
    {
        self::assertEquals(
            [
                'awaiting_approve',
                'awaiting_packaging',
                'awaiting_deliver',
                'delivering',
                'delivered',
                'cancelled',
            ],
            Status::getList()
        );
    }
}
