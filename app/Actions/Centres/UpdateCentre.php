<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateCentre
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(InspectionCentre $centre, User $actor, array $data): InspectionCentre
    {
        Gate::forUser($actor)->authorize('update', $centre);

        $centre->fill(Arr::only($data, [
            'region_id',
            'operator_name',
            'city',
            'phone',
            'email',
            'latitude',
            'longitude',
        ]));
        $centre->save();

        ContentCache::flush();

        return $centre->refresh();
    }
}
