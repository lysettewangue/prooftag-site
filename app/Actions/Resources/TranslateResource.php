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

final class TranslateResource
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Resource $resource, User $actor, array $data): ResourceTranslation
    {
        Gate::forUser($actor)->authorize('translate', $resource);

        $locale = Locale::from((string) $data['locale']);

        $translation = $resource->translations()->updateOrCreate(
            ['locale' => $locale->value],
            Arr::only($data, ['slug', 'title', 'summary', 'meta_title', 'meta_description', 'canonical_path']),
        );

        if (! $translation instanceof ResourceTranslation) {
            throw new DomainRuleViolation('Could not persist the resource translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
