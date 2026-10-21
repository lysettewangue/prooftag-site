<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CentreVerificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionCentre extends Model
{
    use SoftDeletes;

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
     * @return array<string, string>
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
}
