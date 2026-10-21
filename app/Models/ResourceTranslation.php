<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTranslation extends Model
{
    protected $fillable = [
        'resource_id',
        'locale',
        'slug',
        'title',
        'summary',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
