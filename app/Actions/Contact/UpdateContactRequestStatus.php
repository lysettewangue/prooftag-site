<?php

declare(strict_types=1);

namespace App\Actions\Contact;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ContactRequestStatus;
use App\Models\ContactRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class UpdateContactRequestStatus
{
    public function handle(ContactRequest $contact, User $actor, ContactRequestStatus $to): ContactRequest
    {
        Gate::forUser($actor)->authorize('update', $contact);

        if (! $contact->status->canTransitionTo($to)) {
            throw new DomainRuleViolation("Cannot change contact status from {$contact->status->value} to {$to->value}.");
        }

        $contact->forceFill(['status' => $to])->save();

        return $contact->refresh();
    }
}
