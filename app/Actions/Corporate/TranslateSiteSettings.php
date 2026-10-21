<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\SiteSetting;
use App\Models\SiteSettingTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslateSiteSettings
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(SiteSetting $settings, User $actor, array $data): SiteSettingTranslation
    {
        Gate::forUser($actor)->authorize('translate', $settings);

        $locale = Locale::from((string) $data['locale']);

        $translation = $settings->translations()->updateOrCreate(
            ['locale' => $locale->value],
            Arr::only($data, ['tagline', 'meta_title', 'meta_description', 'canonical_path']),
        );

        if (! $translation instanceof SiteSettingTranslation) {
            throw new DomainRuleViolation('Could not persist the settings translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
