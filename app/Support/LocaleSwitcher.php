<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;

final class LocaleSwitcher
{
    /**
     * Build FR/EN switch targets from page hreflang.
     * Missing locale → that locale’s home (P-09 / H-10: never invent a detail path).
     *
     * @param  array<string, string>  $hreflang
     * @return array{fr: array{url: string, available: bool}, en: array{url: string, available: bool}}
     */
    public static function links(array $hreflang = []): array
    {
        $links = [];

        foreach (Locale::codes() as $locale) {
            $candidate = $hreflang[$locale] ?? null;

            if (is_string($candidate) && $candidate !== '') {
                $links[$locale] = [
                    'url' => self::toPath($candidate),
                    'available' => true,
                ];

                continue;
            }

            $links[$locale] = [
                'url' => PublicPath::localized($locale, '/'),
                'available' => false,
            ];
        }

        return $links;
    }

    private static function toPath(string $url): string
    {
        if (str_starts_with($url, '/')) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);

        return is_string($path) && $path !== '' ? $path : '/';
    }
}
