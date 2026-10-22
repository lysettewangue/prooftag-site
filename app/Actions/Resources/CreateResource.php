<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreateResource
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): Resource
    {
        Gate::forUser($actor)->authorize('create', Resource::class);

        $authority = trim((string) ($data['authority'] ?? ''));
        $issuedOn = $data['issued_on'] ?? null;

        $resource = Resource::query()->create([
            'regulatory_status' => ResourceRegulatoryStatus::from((string) $data['regulatory_status']),
            'authority' => $authority === '' ? null : $authority,
            'issued_on' => filled($issuedOn) ? $issuedOn : null,
            'relates_to_ct' => (bool) ($data['relates_to_ct'] ?? false),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $resource;
    }
}
