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

    public static function expertises(string $locale): string
    {
        return "catalog.expertises.{$locale}";
    }

    public static function homePage(string $locale): string
    {
        return self::page('home', $locale);
    }

    public static function page(string $code, string $locale): string
    {
        return "pages.{$code}.{$locale}";
    }

    public static function teamMembers(string $locale): string
    {
        return "catalog.team.{$locale}";
    }

    public static function partners(string $locale): string
    {
        return "catalog.partners.{$locale}";
    }

    public static function securityPoints(string $locale): string
    {
        return "catalog.security_points.{$locale}";
    }

    public static function equipment(string $locale): string
    {
        return "catalog.equipment.{$locale}";
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

    public static function careers(string $locale): string
    {
        return "catalog.careers.{$locale}";
    }

    public static function sitemap(): string
    {
        return 'seo.sitemap';
    }

    /**
     * Cache integer ids only — never Eloquent models (file store can unserialize as __PHP_Incomplete_Class).
     *
     * @param  callable(): list<int>  $resolver
     * @return list<int>
     */
    public static function rememberIds(string $key, callable $resolver): array
    {
        if (Cache::has($key)) {
            $cached = Cache::get($key);

            if (is_array($cached)) {
                return array_values(array_map(
                    static fn (mixed $id): int => (int) $id,
                    $cached,
                ));
            }

            Cache::forget($key);
        }

        $ids = array_values(array_map(
            static fn (mixed $id): int => (int) $id,
            $resolver(),
        ));

        Cache::put($key, $ids, (int) config('cms.cache_ttl', 3600));

        return $ids;
    }

    /**
     * @param  callable(): int|null  $resolver
     */
    public static function rememberNullableId(string $key, callable $resolver): ?int
    {
        if (Cache::has($key)) {
            $cached = Cache::get($key);

            if ($cached === null) {
                return null;
            }

            if (is_int($cached) || (is_string($cached) && ctype_digit($cached))) {
                return (int) $cached;
            }

            Cache::forget($key);
        }

        $id = $resolver();

        Cache::put($key, $id, (int) config('cms.cache_ttl', 3600));

        return $id;
    }

    public static function flush(): void
    {
        foreach (Locale::codes() as $locale) {
            Cache::forget(self::products($locale));
            Cache::forget(self::expertises($locale));
            Cache::forget(self::teamMembers($locale));
            Cache::forget(self::partners($locale));
            Cache::forget(self::securityPoints($locale));
            Cache::forget(self::equipment($locale));
            Cache::forget(self::centres($locale));
            Cache::forget(self::resources($locale));
            Cache::forget(self::posts($locale));
            Cache::forget(self::careers($locale));

            foreach ([
                'home',
                'about',
                'organisation',
                'history',
                'team',
                'partners',
                'careers',
                'contact',
                'legal_notice',
                'privacy',
                'technical_inspection',
            ] as $code) {
                Cache::forget(self::page($code, $locale));
            }
        }

        Cache::forget(self::sitemap());
    }
}
