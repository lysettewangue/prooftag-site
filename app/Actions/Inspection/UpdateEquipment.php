<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Enums\EquipmentLifecycle;
use App\Models\Equipment;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateEquipment
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Equipment $equipment, User $actor, array $data): Equipment
    {
        Gate::forUser($actor)->authorize('update', $equipment);

        if (isset($data['lifecycle'])) {
            $equipment->lifecycle = EquipmentLifecycle::from((string) $data['lifecycle']);
        }

        $equipment->fill(Arr::only($data, ['sort_order']));
        $equipment->save();

        ContentCache::flush();

        return $equipment->refresh();
    }
}
