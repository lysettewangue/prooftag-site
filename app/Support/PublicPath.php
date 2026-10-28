<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use App\Models\Career;
use App\Models\Equipment;
use App\Models\Expertise;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;

final class PublicPath
{
    public static function localized(string $locale, string $path): string
    {
        $path = '/'.ltrim($path, '/');

        if ($path === '/') {
            return $locale === Locale::English->value ? '/en' : '/';
        }

        return $locale === Locale::English->value ? '/en'.$path : $path;
    }

    public static function canonical(string $path): string
    {
        return rtrim((string) config('cms.canonical_host'), '/').$path;
    }

    public static function for(Model $model, string $locale): ?string
    {
        if (! method_exists($model, 'translationFor')) {
            return null;
        }

        $translation = $model->translationFor($locale);

        if ($translation === null) {
            return null;
        }

        if ($model instanceof Page) {
            $slug = (string) $translation->slug;

            return self::localized($locale, $slug === '' ? '/' : $slug);
        }

        $slug = (string) $translation->slug;

        if ($model instanceof Product) {
            return self::prefixed($locale, 'product', $slug);
        }

        if ($model instanceof Expertise) {
            return self::prefixed($locale, 'expertise', $slug);
        }

        if ($model instanceof Equipment) {
            return self::prefixed($locale, 'equipment', $slug);
        }

        if ($model instanceof InspectionCentre) {
            return self::prefixed($locale, 'centre', $slug);
        }

        if ($model instanceof Post) {
            return self::prefixed($locale, 'post', $slug);
        }

        if ($model instanceof Resource) {
            return self::prefixed($locale, 'resource', $slug);
        }

        if ($model instanceof Career) {
            return self::prefixed($locale, 'career', $slug);
        }

        return null;
    }

    public static function prefixed(string $locale, string $type, string $slug): string
    {
        /** @var array<string, array<string, string>> $prefixes */
        $prefixes = config('cms.path_prefixes', []);
        $prefix = $prefixes[$type][$locale] ?? '/';

        return self::localized($locale, trim($prefix, '/').'/'.$slug);
    }
}
