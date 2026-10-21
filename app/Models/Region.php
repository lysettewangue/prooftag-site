<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RegionCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
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
}
