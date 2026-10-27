<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;

final class PublicNavigation
{
    /**
     * @var array<string, array{fr: string, en: string}>
     */
    private const PATHS = [
        'about' => ['fr' => 'a-propos', 'en' => 'about'],
        'products' => ['fr' => 'produits', 'en' => 'products'],
        'expertise' => ['fr' => 'expertises', 'en' => 'expertise'],
        'technical_inspection' => ['fr' => 'controle-technique', 'en' => 'technical-inspection'],
        'centres' => ['fr' => 'centres-de-controle', 'en' => 'inspection-centres'],
        'resources' => ['fr' => 'ressources', 'en' => 'resources'],
        'news' => ['fr' => 'actualites', 'en' => 'news'],
        'contact' => ['fr' => 'contact', 'en' => 'contact'],
    ];

    /**
     * @return list<array{key: string, label: string, url: string, active: bool}>
     */
    public static function items(string $locale, string $currentPath = ''): array
    {
        $locale = self::supportedLocale($locale);

        return array_map(
            static function (string $key) use ($locale, $currentPath): array {
                $url = PublicPath::localized($locale, self::PATHS[$key][$locale]);

                return [
                    'key' => $key,
                    'label' => trans('ui.navigation.items.'.$key),
                    'url' => $url,
                    'active' => self::matches($currentPath, $url),
                ];
            },
            array_keys(self::PATHS),
        );
    }

    public static function searchUrl(string $locale): string
    {
        $locale = self::supportedLocale($locale);

        return PublicPath::localized($locale, $locale === Locale::English->value ? 'search' : 'recherche');
    }

    private static function matches(string $currentPath, string $targetUrl): bool
    {
        $currentPath = trim($currentPath, '/');
        $targetPath = trim($targetUrl, '/');

        return $currentPath === $targetPath || str_starts_with($currentPath, $targetPath.'/');
    }

    private static function supportedLocale(string $locale): string
    {
        return $locale === Locale::English->value ? Locale::English->value : Locale::French->value;
    }
}
