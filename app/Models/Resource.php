<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Publishable as PublishableContract;
use App\Domain\Contracts\Translatable;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property ResourceRegulatoryStatus $regulatory_status
 * @property string|null $authority
 * @property bool $relates_to_ct
 */
class Resource extends Model implements HasMedia, PublishableContract, Translatable
{
    use HasCmsMedia, HasTranslations, Publishable;

    protected $fillable = [
        'regulatory_status',
        'authority',
        'issued_on',
        'relates_to_ct',
        'publication_status',
        'published_at',
        'scheduled_for',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'regulatory_status' => ResourceRegulatoryStatus::class,
            'issued_on' => 'date',
            'relates_to_ct' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ResourceTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ResourceTranslation::class);
    }

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'resource_product');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('documents')->useDisk('documents')->singleFile();
    }

    public function mayEnterPublished(): bool
    {
        return $this->getFirstMedia('documents') !== null;
    }
}
