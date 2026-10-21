<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactReceived;
use App\Mail\ContactReceivedMail;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;

final class SendContactReceivedMail
{
    public function handle(ContactReceived $event): void
    {
        $request = $event->contactRequest;

        if (! $request->dispatchesInboundMail()) {
            return;
        }

        $settings = SiteSetting::query()->find(1);
        $to = $settings instanceof SiteSetting ? (string) $settings->email : 'info@prooftagcatis.com';

        Mail::send(new ContactReceivedMail($request, $to));

        $request->forceFill(['notified_at' => now()])->save();
    }
}
