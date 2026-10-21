<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Translatable;
use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class SiteSetting extends Model implements HasMedia, Translatable
{
    use HasCmsMedia, HasTranslations;

    public $incrementing = false;

    protected $fillable = [
        'legal_name',
        'brand',
        'building',
        'city',
        'country',
        'postal_box',
        'telephone',
        'email',
        'canonical_url',
        'social_linkedin',
        'social_facebook',
        'social_x',
        'social_youtube',
        'social_instagram',
        'geo_latitude',
        'geo_longitude',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'geo_latitude' => 'decimal:7',
            'geo_longitude' => 'decimal:7',
        ];
    }

    /**
     * @return HasMany<SiteSettingTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(SiteSettingTranslation::class);
    }
}
