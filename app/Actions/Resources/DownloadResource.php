<?php

declare(strict_types=1);

namespace App\Actions\Resources;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Resource;
use Symfony\Component\HttpFoundation\Response;

final class DownloadResource
{
    public function handle(Resource $resource, Locale $locale): Response
    {
        if (! $resource->isPubliclyVisibleIn($locale->value, now())) {
            throw new DomainRuleViolation('Resource is not publicly downloadable.');
        }

        $media = $resource->getFirstMedia('documents');

        if ($media === null) {
            throw new DomainRuleViolation('No document is attached to this resource.');
        }

        return $media->toResponse(request());
    }
}
