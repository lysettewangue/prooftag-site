<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Actions\Publication\TransitionPublication;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\Resource;
use App\Models\User;

final class PublishResource
{
    public function handle(Resource $resource, User $actor): Resource
    {
        $published = (new TransitionPublication)->handle($resource, PublicationStatus::Published, $actor);

        if (! $published instanceof Resource) {
            throw new DomainRuleViolation('Unexpected entity.');
        }

        return $published;
    }
}
