<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Centres\CentreVisibility;
use App\Domain\Contracts\Translatable;
use App\Enums\CentreVerificationStatus;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property CentreVerificationStatus $verification_status
 */
class InspectionCentre extends Model implements Translatable
{
    use HasTranslations, SoftDeletes;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'verification_status' => CentreVerificationStatus::Unverified->value,
    ];

    protected $fillable = [
        'region_id',
        'operator_name',
        'city',
        'phone',
        'email',
        'latitude',
        'longitude',
        'verification_status',
        'last_verified_at',
        'verified_by',
        'sort_order',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'verification_status' => CentreVerificationStatus::class,
            'last_verified_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * @return HasMany<InspectionCentreTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(InspectionCentreTranslation::class);
    }

    public function isPubliclyListed(): bool
    {
        return CentreVisibility::isPubliclyListed($this->verification_status);
    }

    public function isPubliclyVisibleIn(string $locale): bool
    {
        return $this->isPubliclyListed() && $this->hasLocale($locale);
    }

    public function canTransitionTo(CentreVerificationStatus $to): bool
    {
        return $this->verification_status->canTransitionTo($to);
    }

    /**
     * @param  Builder<covariant Model>  $query
     */
    public function scopePubliclyListed(Builder $query): void
    {
        $query->where('verification_status', CentreVerificationStatus::Active);
    }
}
