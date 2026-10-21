<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class MarkCentreTemporarilyUnavailable
{
    public function handle(InspectionCentre $centre, User $actor): InspectionCentre
    {
        Gate::forUser($actor)->authorize('markUnavailable', $centre);

        if (! $centre->canTransitionTo(CentreVerificationStatus::TemporarilyUnavailable)) {
            throw new DomainRuleViolation('This centre cannot be marked temporarily unavailable from its current status.');
        }

        $centre->forceFill([
            'verification_status' => CentreVerificationStatus::TemporarilyUnavailable,
        ])->save();

        ContentCache::flush();

        return $centre->refresh();
    }
}
