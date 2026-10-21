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

final class TranslateCategory
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Category $category, User $actor, array $data): CategoryTranslation
    {
        Gate::forUser($actor)->authorize('translate', $category);

        $locale = Locale::from((string) $data['locale']);

        $translation = $category->translations()->updateOrCreate(
            ['locale' => $locale->value],
            Arr::only($data, ['slug', 'name', 'meta_title', 'meta_description', 'canonical_path']),
        );

        if (! $translation instanceof CategoryTranslation) {
            throw new DomainRuleViolation('Could not persist the category translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
