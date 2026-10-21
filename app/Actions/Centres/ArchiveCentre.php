<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class ArchiveCentre
{
    public function handle(InspectionCentre $centre, User $actor): InspectionCentre
    {
        Gate::forUser($actor)->authorize('archive', $centre);

        if (! $centre->canTransitionTo(CentreVerificationStatus::Archived)) {
            throw new DomainRuleViolation('This centre cannot be archived from its current status.');
        }

        $centre->forceFill([
            'verification_status' => CentreVerificationStatus::Archived,
        ])->save();

        ContentCache::flush();

        return $centre->refresh();
    }
}
