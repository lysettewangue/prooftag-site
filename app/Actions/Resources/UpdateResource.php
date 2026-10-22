<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateResource
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Resource $resource, User $actor, array $data): Resource
    {
        Gate::forUser($actor)->authorize('update', $resource);

        if (isset($data['regulatory_status'])) {
            $resource->regulatory_status = ResourceRegulatoryStatus::from((string) $data['regulatory_status']);
        }

        if (array_key_exists('authority', $data)) {
            $authority = trim((string) $data['authority']);
            $data['authority'] = $authority === '' ? null : $authority;
        }

        if (array_key_exists('issued_on', $data) && ! filled($data['issued_on'])) {
            $data['issued_on'] = null;
        }

        $resource->fill(Arr::only($data, ['authority', 'issued_on', 'relates_to_ct']));
        $resource->save();

        if (array_key_exists('product_ids', $data) && is_array($data['product_ids'])) {
            $resource->products()->sync($data['product_ids']);
        }

        ContentCache::flush();

        return $resource->refresh();
    }
}
