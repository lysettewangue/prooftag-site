<?php

declare(strict_types=1);

namespace App\Actions\Centres;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Events\CentreVerified;
use App\Models\InspectionCentre;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class VerifyCentre
{
    public function handle(InspectionCentre $centre, User $actor): InspectionCentre
    {
        Gate::forUser($actor)->authorize('verify', $centre);

        if (! $centre->canTransitionTo(CentreVerificationStatus::Active)) {
            throw new DomainRuleViolation('This centre cannot be verified from its current status.');
        }

        $centre->forceFill([
            'verification_status' => CentreVerificationStatus::Active,
            'last_verified_at' => now(),
            'verified_by' => $actor->id,
        ])->save();

        CentreVerified::dispatch($centre, $actor);

        return $centre->refresh();
    }
}
