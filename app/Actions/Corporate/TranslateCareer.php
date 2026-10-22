<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Career;
use App\Models\CareerTranslation;
use App\Models\User;
use App\Support\BodyContent;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateCareer
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Career $career, User $actor, array $data): CareerTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $career,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Career title is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($title);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Career slug is required.');
        }

        $duplicate = CareerTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('career_id', '!=', $career->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Career slugs must be unique per locale.');
        }

        $translation = $career->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'body' => BodyContent::fromForm($data['body'] ?? null),
                ],
            ),
        );

        if (! $translation instanceof CareerTranslation) {
            throw new DomainRuleViolation('Could not persist the career translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
