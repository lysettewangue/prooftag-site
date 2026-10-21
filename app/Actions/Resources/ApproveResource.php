<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Actions\Publication\TransitionPublication;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\Resource;
use App\Models\User;

final class ApproveResource
{
    public function handle(Resource $resource, User $actor): Resource
    {
        $approved = (new TransitionPublication)->handle($resource, PublicationStatus::Approved, $actor);

        if (! $approved instanceof Resource) {
            throw new DomainRuleViolation('Unexpected entity.');
        }

        return $approved;
    }
}
