<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Publishable as PublishableContract;
use App\Domain\Contracts\Translatable;
use App\Enums\CareerKind;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property CareerKind $kind
 * @property string|null $application_url
 */
class Career extends Model implements PublishableContract, Translatable
{
    use HasTranslations, Publishable;

    protected $fillable = [
        'kind',
        'closes_at',
        'application_url',
        'publication_status',
        'published_at',
        'scheduled_for',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => CareerKind::class,
            'closes_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<CareerTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CareerTranslation::class);
    }

    public function isClosed(?DateTimeInterface $now = null): bool
    {
        return $this->closes_at !== null && $this->closes_at->lte($now ?? now());
    }
}
