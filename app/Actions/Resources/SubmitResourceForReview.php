<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Actions\Publication\TransitionPublication;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\Resource;
use App\Models\User;

final class SubmitResourceForReview
{
    public function handle(Resource $resource, User $actor): Resource
    {
        $submitted = (new TransitionPublication)->handle($resource, PublicationStatus::UnderReview, $actor);

        if (! $submitted instanceof Resource) {
            throw new DomainRuleViolation('Unexpected entity.');
        }

        return $submitted;
    }
}
