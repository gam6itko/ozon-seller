<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Utils;

final class WithResolver
{
    public static function getKeys(int $version = 2, string $postingScheme = 'fbs'): array
    {
        switch ("$version-$postingScheme") {
            case '2-fbo':
                return ['analytics_data', 'financial_data'];
            default:
                return ['analytics_data', 'barcodes', 'financial_data'];
        }
    }

    public static function getDefaults(int $version = 2, string $postingScheme = 'fbs'): array
    {
        $keys = self::getKeys($version, $postingScheme);

        return array_combine($keys, array_pad([], count($keys), false));
    }

    public static function resolve(array $with = [], int $version = 2, string $postingScheme = 'fbs'): array
    {
        if (isset($with['with'])) {
            $with = $with['with'];
        }

        return array_merge(
            self::getDefaults($version, $postingScheme),
            ArrayHelper::pick($with, self::getKeys($version, $postingScheme))
        );
    }
}
