<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Enums\CentreVerificationStatus;
use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class ImportCentre
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): InspectionCentre
    {
        Gate::forUser($actor)->authorize('create', InspectionCentre::class);

        $centre = InspectionCentre::query()->create([
            'region_id' => (int) $data['region_id'],
            'operator_name' => (string) $data['operator_name'],
            'city' => (string) $data['city'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'verification_status' => CentreVerificationStatus::Unverified,
        ]);

        $name = (string) ($data['name'] ?? $data['operator_name']);
        $slug = (string) ($data['slug'] ?? str()->slug($name));

        foreach (Locale::cases() as $locale) {
            $centre->translations()->create([
                'locale' => $locale->value,
                'slug' => $slug,
                'name' => $name,
                'address_line' => $data['address_line'] ?? null,
                'body' => $data['body'] ?? null,
            ]);
        }

        ContentCache::flush();

        return $centre->refresh();
    }
}
