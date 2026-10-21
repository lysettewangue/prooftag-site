<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CareerKind;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Career extends Model
{
    use Publishable;

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
}
