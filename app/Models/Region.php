<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Translatable;
use App\Enums\RegionCode;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model implements Translatable
{
    use HasTranslations;

    protected $fillable = [
        'code',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'code' => RegionCode::class,
        ];
    }

    /**
     * @return HasMany<RegionTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(RegionTranslation::class);
    }

    /**
     * @return HasMany<InspectionCentre, $this>
     */
    public function centres(): HasMany
    {
        return $this->hasMany(InspectionCentre::class);
    }
}
