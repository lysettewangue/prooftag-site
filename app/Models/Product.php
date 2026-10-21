<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductCode;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use Publishable;

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
     * @return array<string, string>
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
}
