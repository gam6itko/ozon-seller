<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class ProductState
{
    const PROCESSED = 'processed';
    const PROCESSING = 'processing';
    const MODERATING = 'moderating';
    const FAILED_MODERATION = 'failed_moderation';
    const FAILED_VALIDATION = 'failed_validation';
    const FAILED = 'failed';
}
