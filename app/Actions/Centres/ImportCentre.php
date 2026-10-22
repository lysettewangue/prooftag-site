<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\Region;
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

        $regionId = (int) ($data['region_id'] ?? 0);
        $operator = trim((string) ($data['operator_name'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));

        if ($regionId < 1 || Region::query()->whereKey($regionId)->doesntExist()) {
            throw new DomainRuleViolation('A seeded Cameroon region is required.');
        }

        if ($operator === '' || $city === '') {
            throw new DomainRuleViolation('Operator name and city are required.');
        }

        $centre = InspectionCentre::query()->create([
            'region_id' => $regionId,
            'operator_name' => $operator,
            'city' => $city,
            'phone' => $this->nullableString($data['phone'] ?? null),
            'email' => $this->nullableString($data['email'] ?? null),
            'latitude' => $this->nullableString($data['latitude'] ?? null),
            'longitude' => $this->nullableString($data['longitude'] ?? null),
            'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : null,
            'verification_status' => CentreVerificationStatus::Unverified,
        ]);

        (new TranslateCentre)->handle($centre, $actor, [
            'locale' => Locale::French->value,
            'name' => (string) ($data['name'] ?? $operator),
            'slug' => (string) ($data['slug'] ?? ''),
            'address_line' => $data['address_line'] ?? null,
            'body' => $data['body'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_path' => $data['canonical_path'] ?? null,
        ]);

        ContentCache::flush();

        return $centre->refresh();
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $trimmed = trim((string) $value);

        return $trimmed === '' ? null : $trimmed;
    }
}
