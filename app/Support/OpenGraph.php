<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

final class OpenGraph
{
    public static function localeTag(string $locale): string
    {
        return $locale === Locale::English->value ? 'en_GB' : 'fr_FR';
    }

    public static function fallbackImageUrl(): ?string
    {
        if (Schema::hasTable('site_settings')) {
            $settings = SiteSetting::query()->find(1);
            $logo = $settings?->getFirstMedia('images');

            if ($logo !== null) {
                return self::absoluteUrl($logo->getUrl());
            }
        }

        return self::absoluteUrl(asset('images/brand/logo.png'));
    }

    public static function absoluteUrl(string $url): string
    {
        $host = rtrim((string) config('cms.canonical_host'), '/');

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            $query = parse_url($url, PHP_URL_QUERY);
            $suffix = is_string($query) && $query !== '' ? '?'.$query : '';

            return $host.$path.$suffix;
        }

        return $host.'/'.ltrim($url, '/');
    }

    public static function robots(): string
    {
        return (string) config('cms.robots', 'index,follow');
    }
}
