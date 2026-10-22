<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Equipment;
use App\Models\EquipmentTranslation;
use App\Models\User;
use App\Support\BodyContent;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateEquipment
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Equipment $equipment, User $actor, array $data): EquipmentTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $equipment,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Equipment title is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($title);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Equipment slug is required.');
        }

        $duplicate = EquipmentTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('equipment_id', '!=', $equipment->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Equipment slugs must be unique per locale.');
        }

        $translation = $equipment->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'body' => BodyContent::fromForm($data['body'] ?? null),
                ],
            ),
        );

        if (! $translation instanceof EquipmentTranslation) {
            throw new DomainRuleViolation('Could not persist the equipment translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
