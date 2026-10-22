<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Partner;
use App\Models\PartnerTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslatePartner
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Partner $partner, User $actor, array $data): PartnerTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $partner,
        );

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($partner->name);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Partner slug is required.');
        }

        $duplicate = PartnerTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('partner_id', '!=', $partner->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Partner slugs must be unique per locale.');
        }

        $blurb = $data['blurb'] ?? null;
        $blurb = is_string($blurb) ? trim($blurb) : null;

        $translation = $partner->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'blurb' => $blurb === '' ? null : $blurb,
                ],
            ),
        );

        if (! $translation instanceof PartnerTranslation) {
            throw new DomainRuleViolation('Could not persist the partner translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
