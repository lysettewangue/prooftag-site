<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\Partner;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreatePartner
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): Partner
    {
        Gate::forUser($actor)->authorize('create', Partner::class);

        $name = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            throw new DomainRuleViolation('Partner name is required.');
        }

        $partner = Partner::query()->create([
            'name' => $name,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'url' => $this->normalizedUrl($data['url'] ?? null),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $partner;
    }

    private function normalizedUrl(mixed $value): ?string
    {
        $url = trim((string) $value);

        if ($url === '') {
            return null;
        }

        if (strlen($url) > 255 || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new DomainRuleViolation('Partner URL must be a valid address.');
        }

        return $url;
    }
}
