<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

trait Publishable
{
    use SoftDeletes;

    public function initializePublishable(): void
    {
        $this->mergeCasts([
            'publication_status' => PublicationStatus::class,
            'published_at' => 'datetime',
            'scheduled_for' => 'datetime',
        ]);
    }
}
