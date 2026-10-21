<?php

declare(strict_types=1);

namespace App\Actions\Expertise;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Domain\Expertise\ExpertiseTitleRules;
use App\Enums\Locale;
use App\Models\Expertise;
use App\Models\ExpertiseTranslation;
use App\Models\User;
use App\Support\BodyContent;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslateExpertise
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Expertise $expertise, User $actor, array $data): ExpertiseTranslation
    {
        Gate::forUser($actor)->authorize('translate', $expertise);

        $locale = Locale::from((string) $data['locale']);
        $slug = $expertise->code->slugs()[$locale->value];
        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Expertise title is required.');
        }

        if (! ExpertiseTitleRules::mayUseTitle($title)) {
            throw new DomainRuleViolation('Expertise titles must not copy product names.');
        }

        if (isset($data['slug']) && $data['slug'] !== $slug) {
            throw new DomainRuleViolation('Expertise slugs are frozen to the sitemap fragments.');
        }

        $translation = $expertise->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['summary', 'meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'body' => BodyContent::fromForm($data['body'] ?? null),
                ],
            ),
        );

        if (! $translation instanceof ExpertiseTranslation) {
            throw new DomainRuleViolation('Could not persist the expertise translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
