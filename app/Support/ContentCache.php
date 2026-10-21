<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use Illuminate\Support\Facades\Cache;

final class ContentCache
{
    public static function products(string $locale): string
    {
        return "catalog.products.{$locale}";
    }

    public static function centres(string $locale): string
    {
        return "catalog.centres.{$locale}";
    }

    public static function resources(string $locale): string
    {
        return "catalog.resources.{$locale}";
    }

    public static function posts(string $locale): string
    {
        return "catalog.posts.{$locale}";
    }

    public static function sitemap(): string
    {
        return 'seo.sitemap';
    }

    public static function flush(): void
    {
        foreach (Locale::codes() as $locale) {
            Cache::forget(self::products($locale));
            Cache::forget(self::centres($locale));
            Cache::forget(self::resources($locale));
            Cache::forget(self::posts($locale));
        }

        Cache::forget(self::sitemap());
    }
}
