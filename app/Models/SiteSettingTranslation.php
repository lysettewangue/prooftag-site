<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSettingTranslation extends Model
{
    protected $fillable = [
        'site_setting_id',
        'locale',
        'tagline',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    /**
     * @return BelongsTo<SiteSetting, $this>
     */
    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class);
    }
}
