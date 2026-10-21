<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Model
 *
 * @method HasMany translations()
 */
trait HasTranslations
{
    public function translationFor(string $locale): mixed
    {
        if ($this->relationLoaded('translations')) {
            return $this->getRelation('translations')->firstWhere('locale', $locale);
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    public function hasLocale(string $locale): bool
    {
        return $this->translationFor($locale) !== null;
    }

    /**
     * @param  Builder<covariant Model>  $query
     */
    public function scopeForLocale(Builder $query, string $locale): void
    {
        $query->whereHas('translations', function (Builder $translations) use ($locale): void {
            $translations->where('locale', $locale);
        });
    }

    /**
     * @param  Builder<covariant Model>  $query
     */
    public function scopeWithLocaleSlug(Builder $query, string $locale, string $slug): void
    {
        $query->whereHas('translations', function (Builder $translations) use ($locale, $slug): void {
            $translations->where('locale', $locale)->where('slug', $slug);
        });
    }
}
