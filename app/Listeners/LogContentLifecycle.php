<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CentreVerified;
use App\Events\ContentPublished;
use App\Support\ContentCache;

final class LogContentLifecycle
{
    public function published(ContentPublished $event): void
    {
        activity('staff')
            ->causedBy($event->actor)
            ->performedOn($event->model)
            ->event('published')
            ->log('published');

        ContentCache::flush();
    }

    public function centreVerified(CentreVerified $event): void
    {
        activity('staff')
            ->causedBy($event->actor)
            ->performedOn($event->centre)
            ->event('verified')
            ->log('centre verified');

        ContentCache::flush();
    }
}
