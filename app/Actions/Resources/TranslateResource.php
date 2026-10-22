<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Resource;
use App\Models\ResourceTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateResource
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Resource $resource, User $actor, array $data): ResourceTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $resource,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Resource title is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($title);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Resource slug is required.');
        }

        $duplicate = ResourceTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('resource_id', '!=', $resource->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Resource slugs must be unique per locale.');
        }

        $summary = $data['summary'] ?? null;
        $summary = is_string($summary) ? trim($summary) : null;

        $translation = $resource->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'summary' => $summary === '' ? null : $summary,
                ],
            ),
        );

        if (! $translation instanceof ResourceTranslation) {
            throw new DomainRuleViolation('Could not persist the resource translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
