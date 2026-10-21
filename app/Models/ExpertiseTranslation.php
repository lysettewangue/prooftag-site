<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertiseTranslation extends Model
{
    protected $fillable = [
        'expertise_id',
        'locale',
        'slug',
        'title',
        'summary',
        'body',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'body' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Expertise, $this>
     */
    public function expertise(): BelongsTo
    {
        return $this->belongsTo(Expertise::class);
    }
}
