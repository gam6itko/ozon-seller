<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class PostingScheme
{
    public const CROSSBORDER = 'crossborder';
    public const FBO = 'fbo';
    public const FBS = 'fbs';

    public static function all(): array
    {
        return [
            self::CROSSBORDER,
            self::FBO,
            self::FBS,
        ];
    }
}
