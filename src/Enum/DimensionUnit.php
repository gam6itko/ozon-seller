<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Enum;

final class DimensionUnit
{
    public const MILLIMETERS = 'mm';
    public const CENTIMETRES = 'cm';
    public const INCHES = 'in';

    /** @deprecated use MILLIMETERS */
    public const Millimeters = 'mm';
    /** @deprecated use CENTIMETRES */
    public const Centimetres = 'cm';
    /** @deprecated use INCHES */
    public const Inches = 'in';
}
