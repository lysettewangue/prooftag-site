<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Domain\Contracts\Translatable;
use App\Enums\Locale;
use App\Models\SiteSetting;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Model;

final class SeoMetaResolver
{
    public function forPage(string $locale, string $path): SeoMeta
    {
        $defaults = $this->defaults($locale);
        $normalized = PublicPath::localized($locale, $path);

        return new SeoMeta(
            title: $defaults['title'],
            description: $defaults['description'],
            canonical: PublicPath::canonical($normalized),
            hreflang: $this->pairForPath($path),
        );
    }

    public function forModel(Translatable&Model $model, string $locale): SeoMeta
    {
        $defaults = $this->defaults($locale);
        $translation = $model->translationFor($locale);
        $path = PublicPath::for($model, $locale) ?? PublicPath::localized($locale, '/');
        $overridePath = is_object($translation) ? ($translation->canonical_path ?? null) : null;
        $canonicalPath = is_string($overridePath) && $overridePath !== ''
            ? (str_starts_with($overridePath, 'http') ? $overridePath : PublicPath::canonical($overridePath))
            : PublicPath::canonical($path);

        $title = is_object($translation) ? ($translation->meta_title ?: $this->fallbackTitle($translation, $defaults['title'])) : $defaults['title'];
        $description = is_object($translation) ? ($translation->meta_description ?: $defaults['description']) : $defaults['description'];

        return new SeoMeta(
            title: (string) $title,
            description: $description !== null ? (string) $description : null,
            canonical: $canonicalPath,
            hreflang: $this->hreflangFor($model),
        );
    }

    /**
     * @return array{title: string, description: string|null}
     */
    private function defaults(string $locale): array
    {
        $settings = SiteSetting::query()->find(1);
        $translation = $settings instanceof SiteSetting ? $settings->translationFor($locale) : null;
        $brand = $settings instanceof SiteSetting ? (string) $settings->brand : 'PROOFTAG-CATIS';

        return [
            'title' => (string) ($translation?->meta_title ?: $brand),
            'description' => $translation?->meta_description,
        ];
    }

    private function fallbackTitle(object $translation, string $default): string
    {
        foreach (['title', 'name', 'summary'] as $field) {
            if (isset($translation->{$field}) && is_string($translation->{$field}) && $translation->{$field} !== '') {
                return $translation->{$field};
            }
        }

        return $default;
    }

    /**
     * @return array<string, string>
     */
    private function hreflangFor(Translatable&Model $model): array
    {
        $pairs = [];

        foreach (Locale::codes() as $locale) {
            if (! $model->hasLocale($locale)) {
                continue;
            }

            $path = PublicPath::for($model, $locale);

            if ($path === null) {
                continue;
            }

            $pairs[$locale] = PublicPath::canonical($path);
        }

        return $pairs;
    }

    /**
     * @return array<string, string>
     */
    private function pairForPath(string $path): array
    {
        $path = ltrim($path, '/');

        return [
            Locale::French->value => PublicPath::canonical(PublicPath::localized(Locale::French->value, $path)),
            Locale::English->value => PublicPath::canonical(PublicPath::localized(Locale::English->value, $path)),
        ];
    }
}
