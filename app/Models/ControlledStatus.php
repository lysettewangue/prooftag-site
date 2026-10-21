<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ControlledStatusSet;
use Illuminate\Database\Eloquent\Model;

class ControlledStatus extends Model
{
    protected $fillable = [
        'set',
        'code',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'set' => ControlledStatusSet::class,
            'sort_order' => 'integer',
        ];
    }
}
