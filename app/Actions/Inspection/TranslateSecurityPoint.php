<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\SecurityPoint;
use App\Models\SecurityPointTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslateSecurityPoint
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(SecurityPoint $point, User $actor, array $data): SecurityPointTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $point,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Security point title is required.');
        }

        $translation = $point->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(Arr::only($data, ['body']), ['title' => $title]),
        );

        if (! $translation instanceof SecurityPointTranslation) {
            throw new DomainRuleViolation('Could not persist the security point translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
