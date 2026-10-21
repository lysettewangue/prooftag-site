<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Publishable as PublishableContract;
use App\Domain\Contracts\Translatable;
use App\Enums\EquipmentLifecycle;
use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Equipment extends Model implements HasMedia, PublishableContract, Translatable
{
    use HasCmsMedia, HasTranslations, Publishable;

    protected $table = 'equipment';

    protected $fillable = [
        'lifecycle',
        'sort_order',
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
            'lifecycle' => EquipmentLifecycle::class,
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<EquipmentTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(EquipmentTranslation::class);
    }
}
