<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    use Publishable;

    protected $fillable = [
        'full_name',
        'sort_order',
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
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<TeamMemberTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TeamMemberTranslation::class);
    }
}
