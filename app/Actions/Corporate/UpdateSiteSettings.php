<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Models\SiteSetting;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateSiteSettings
{
    /**
     * Frozen identity fields from the contact master record are ignored.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(SiteSetting $settings, User $actor, array $data): SiteSetting
    {
        Gate::forUser($actor)->authorize('update', $settings);

        $settings->fill(Arr::only($data, [
            'social_linkedin',
            'social_facebook',
            'social_x',
            'social_youtube',
            'social_instagram',
            'geo_latitude',
            'geo_longitude',
        ]));
        $settings->save();

        ContentCache::flush();

        return $settings->refresh();
    }
}
