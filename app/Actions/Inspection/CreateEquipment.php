<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Enums\EquipmentLifecycle;
use App\Enums\PublicationStatus;
use App\Models\Equipment;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreateEquipment
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): Equipment
    {
        Gate::forUser($actor)->authorize('create', Equipment::class);

        $equipment = Equipment::query()->create([
            'lifecycle' => EquipmentLifecycle::from((string) ($data['lifecycle'] ?? EquipmentLifecycle::Current->value)),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $equipment;
    }
}
