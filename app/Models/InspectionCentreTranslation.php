<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionCentreTranslation extends Model
{
    protected $fillable = [
        'inspection_centre_id',
        'locale',
        'slug',
        'name',
        'address_line',
        'body',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    /**
     * @return BelongsTo<InspectionCentre, $this>
     */
    public function inspectionCentre(): BelongsTo
    {
        return $this->belongsTo(InspectionCentre::class);
    }
}
