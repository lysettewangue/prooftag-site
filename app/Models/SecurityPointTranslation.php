<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityPointTranslation extends Model
{
    protected $fillable = [
        'security_point_id',
        'locale',
        'title',
        'body',
    ];

    /**
     * @return BelongsTo<SecurityPoint, $this>
     */
    public function securityPoint(): BelongsTo
    {
        return $this->belongsTo(SecurityPoint::class);
    }
}
