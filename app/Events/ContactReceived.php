<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ContactRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ContactReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContactRequest $contactRequest,
    ) {}
}
