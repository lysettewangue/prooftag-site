<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateCategory
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Category $category, User $actor, array $data): CategoryTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $category,
        );

        $name = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            throw new DomainRuleViolation('Category name is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($name);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Category slug is required.');
        }

        $duplicate = CategoryTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('category_id', '!=', $category->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Category slugs must be unique per locale.');
        }

        $translation = $category->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'name' => $name,
                ],
            ),
        );

        if (! $translation instanceof CategoryTranslation) {
            throw new DomainRuleViolation('Could not persist the category translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
