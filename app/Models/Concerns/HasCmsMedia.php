<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\MediaLibrary\InteractsWithMedia;

trait HasCmsMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }
}
