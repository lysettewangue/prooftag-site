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

final class TranslateCentre
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(InspectionCentre $centre, User $actor, array $data): InspectionCentreTranslation
    {
        Gate::forUser($actor)->authorize('translate', $centre);

        $locale = Locale::from((string) $data['locale']);

        $translation = $centre->translations()->updateOrCreate(
            ['locale' => $locale->value],
            Arr::only($data, ['slug', 'name', 'address_line', 'body', 'meta_title', 'meta_description', 'canonical_path']),
        );

        if (! $translation instanceof InspectionCentreTranslation) {
            throw new DomainRuleViolation('Could not persist the centre translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
