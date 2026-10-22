<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\InspectionCentre;
use App\Models\Region;
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

        if (isset($data['region_id'])) {
            $regionId = (int) $data['region_id'];

            if ($regionId < 1 || Region::query()->whereKey($regionId)->doesntExist()) {
                throw new DomainRuleViolation('A seeded Cameroon region is required.');
            }

            $data['region_id'] = $regionId;
        }

        foreach (['phone', 'email', 'latitude', 'longitude'] as $field) {
            if (array_key_exists($field, $data) && ($data[$field] === '' || $data[$field] === null)) {
                $data[$field] = null;
            }
        }

        $centre->fill(Arr::only($data, [
            'region_id',
            'operator_name',
            'city',
            'phone',
            'email',
            'latitude',
            'longitude',
            'sort_order',
        ]));
        $centre->save();

        ContentCache::flush();

        return $centre->refresh();
    }
}
