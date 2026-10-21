<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMemberTranslation extends Model
{
    protected $fillable = [
        'team_member_id',
        'locale',
        'slug',
        'title',
        'bio',
        'meta_title',
        'meta_description',
        'canonical_path',
    ];

    /**
     * @return BelongsTo<TeamMember, $this>
     */
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }
}
