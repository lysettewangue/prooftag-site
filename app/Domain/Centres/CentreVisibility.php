<?php

declare(strict_types=1);

namespace App\Domain\Centres;

use App\Enums\CentreVerificationStatus;

final class CentreVisibility
{
    public static function isPubliclyListed(CentreVerificationStatus $status): bool
    {
        return $status->isPubliclyListed();
    }

    public static function showsMapPin(CentreVerificationStatus $status, ?string $latitude, ?string $longitude): bool
    {
        if (! $status->isPubliclyListed()) {
            return false;
        }

        return $latitude !== null && $latitude !== '' && $longitude !== null && $longitude !== '';
    }
}
