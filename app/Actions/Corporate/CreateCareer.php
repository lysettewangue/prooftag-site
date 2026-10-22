<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CareerKind;
use App\Enums\PublicationStatus;
use App\Models\Career;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreateCareer
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): Career
    {
        Gate::forUser($actor)->authorize('create', Career::class);

        $career = Career::query()->create([
            'kind' => CareerKind::from((string) ($data['kind'] ?? '')),
            'closes_at' => filled($data['closes_at'] ?? null) ? $data['closes_at'] : null,
            'application_url' => $this->normalizedUrl($data['application_url'] ?? null),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $career;
    }

    private function normalizedUrl(mixed $value): ?string
    {
        $url = trim((string) $value);

        if ($url === '') {
            return null;
        }

        if (strlen($url) > 255 || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new DomainRuleViolation('Career application URL must be a valid address.');
        }

        return $url;
    }
}
