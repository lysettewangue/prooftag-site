<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerTranslation extends Model
{
    protected $fillable = [
        'partner_id',
        'locale',
        'slug',
        'blurb',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
