<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\InspectionCentreTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateCentre
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(InspectionCentre $centre, User $actor, array $data): InspectionCentreTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $centre,
        );

        $name = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            throw new DomainRuleViolation('Centre name is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($name);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Centre slug is required.');
        }

        $duplicate = InspectionCentreTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('inspection_centre_id', '!=', $centre->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Centre slugs must be unique per locale.');
        }

        $translation = $centre->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['address_line', 'body', 'meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'name' => $name,
                ],
            ),
        );

        if (! $translation instanceof InspectionCentreTranslation) {
            throw new DomainRuleViolation('Could not persist the centre translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
