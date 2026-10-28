<?php

declare(strict_types=1);

namespace App\Actions\Contact;

use App\Enums\ContactRequestStatus;
use App\Events\ContactReceived;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

final class SubmitContactRequest
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, Request $request): ContactRequest
    {
        $honeypot = (string) config('cms.honeypot_field', 'website');

        if (filled($request->input($honeypot))) {
            return $this->storeSpam($data, $request);
        }

        $this->throttle($request);

        $contact = ContactRequest::query()->create([
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => (string) $data['subject'],
            'message' => (string) $data['message'],
            'locale' => (string) ($data['locale'] ?? app()->getLocale()),
            'status' => ContactRequestStatus::New,
            'ip' => (string) $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        ContactReceived::dispatch($contact);

        return $contact;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function storeSpam(array $data, Request $request): ContactRequest
    {
        return ContactRequest::query()->create([
            'name' => (string) ($data['name'] ?? 'honeypot'),
            'email' => (string) ($data['email'] ?? 'spam@invalid.local'),
            'phone' => $data['phone'] ?? null,
            'subject' => (string) ($data['subject'] ?? 'spam'),
            'message' => (string) ($data['message'] ?? ''),
            'locale' => (string) ($data['locale'] ?? app()->getLocale()),
            'status' => ContactRequestStatus::Spam,
            'ip' => (string) $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);
    }

    private function throttle(Request $request): void
    {
        $max = (int) config('cms.contact_throttle_max', 5);
        $decay = (int) config('cms.contact_throttle_decay_seconds', 60);
        $key = 'contact:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, $max)) {
            throw ValidationException::withMessages([
                'email' => (string) __('ui.contact.throttle'),
            ]);
        }

        RateLimiter::hit($key, $decay);
    }
}
