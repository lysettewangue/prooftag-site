<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Domain\Contracts\Publishable;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\StaffAccess;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class SafeDeleteMedia
{
    public function handle(Media $media, User $actor): void
    {
        if (! StaffAccess::isStaff($actor)) {
            throw new DomainRuleViolation('Only staff may delete media.');
        }

        $owner = $media->model;

        if ($owner instanceof Publishable && $owner->isPubliclyVisible(now())) {
            throw new DomainRuleViolation('Cannot delete media while a published owner still references it.');
        }

        if ($owner instanceof InspectionCentre && $owner->isPubliclyListed()) {
            throw new DomainRuleViolation('Cannot delete media while a published owner still references it.');
        }

        $media->delete();
    }
}
