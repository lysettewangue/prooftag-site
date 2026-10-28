<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Contracts\Publishable as PublishableContract;
use App\Domain\Contracts\Translatable;
use App\Domain\Products\ProductPublishRules;
use App\Enums\ProductCode;
use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property ProductCode $code
 * @property string|null $external_url
 */
class Product extends Model implements HasMedia, PublishableContract, Translatable
{
    use HasCmsMedia, HasTranslations, Publishable;

    protected $fillable = [
        'code',
        'public_name',
        'sort_order',
        'slug_fragment',
        'external_url',
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
            'code' => ProductCode::class,
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<ProductTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * @return BelongsToMany<Expertise, $this>
     */
    public function expertises(): BelongsToMany
    {
        return $this->belongsToMany(Expertise::class, 'product_expertise');
    }

    /**
     * @return BelongsToMany<Resource, $this>
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_product');
    }

    public function mayEnterPublished(): bool
    {
        return ProductPublishRules::mayEnterPublished($this->code, $this->external_url);
    }
}
