<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ResourceRegulatoryStatus;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use Publishable;

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
     * @return array<string, string>
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
}
