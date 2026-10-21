<?php

declare(strict_types=1);

namespace App\Domain\Communication;

use App\Enums\ContactRequestStatus;

final class ContactPipeline
{
    public static function dispatchesInboundMail(ContactRequestStatus $status): bool
    {
        return $status->dispatchesInboundMail();
    }
}
