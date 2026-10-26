<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Support\Colors\Color;

/**
 * Admin palette derived from the cyan accent in the official logo.
 */
final class BrandPalette
{
    public const Azure = '#008FC7';

    public const Cyan = '#00B8E6';

    public const Paper = '#FFFFFF';

    public const AzureDeep = '#00658C';

    public const AzureDark = '#07546D';

    public const AzureSoft = '#CDEFF8';

    public const AzurePale = '#EAF9FD';

    public const AzureMist = '#F3FBFD';

    public const Ink = '#12222B';

    public const Muted = '#55646D';

    public const Line = '#D6DDE1';

    public const Success = '#146C43';

    public const Warning = '#C95037';

    public const Danger = '#B42318';

    /**
     * @return array<int, string>
     */
    public static function filamentPrimary(): array
    {
        return Color::generateV3Palette(self::Azure);
    }

    /**
     * @return array<int, string>
     */
    public static function filamentSuccess(): array
    {
        return Color::generateV3Palette(self::Success);
    }

    /**
     * @return array<int, string>
     */
    public static function filamentWarning(): array
    {
        return Color::generateV3Palette(self::Warning);
    }

    /**
     * @return array<int, string>
     */
    public static function filamentDanger(): array
    {
        return Color::generateV3Palette(self::Danger);
    }
}
