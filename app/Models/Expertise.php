<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExpertiseCode;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expertise extends Model
{
    use Publishable;

    protected $fillable = [
        'code',
        'sort_order',
        'icon',
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
            'code' => ExpertiseCode::class,
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<ExpertiseTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ExpertiseTranslation::class);
    }

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_expertise');
    }
}
