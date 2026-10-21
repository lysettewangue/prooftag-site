<?php

declare(strict_types=1);

namespace App\Domain\Translation;

use App\Enums\Locale;

final class LocaleAvailability
{
    /**
     * Marketing body does not fall back across locales. Missing translation = no URL.
     */
    public static function hasPublicLocale(bool $baseIsPublic, bool $translationExists): bool
    {
        return $baseIsPublic && $translationExists;
    }

    public static function isSupported(string $locale): bool
    {
        return Locale::tryFrom($locale) instanceof Locale;
    }
}
