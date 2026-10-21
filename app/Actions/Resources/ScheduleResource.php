<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Actions\Publication\TransitionPublication;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\Resource;
use App\Models\User;
use DateTimeInterface;

final class ScheduleResource
{
    public function handle(Resource $resource, User $actor, DateTimeInterface $when): Resource
    {
        $scheduled = (new TransitionPublication)->handle($resource, PublicationStatus::Scheduled, $actor, $when);

        if (! $scheduled instanceof Resource) {
            throw new DomainRuleViolation('Unexpected entity.');
        }

        return $scheduled;
    }
}
