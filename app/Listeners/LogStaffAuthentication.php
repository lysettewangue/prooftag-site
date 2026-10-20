<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;

final class LogStaffAuthentication
{
    public function handleLogin(Login $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->event('login')
            ->withProperties($this->context($event->guard))
            ->log('logged_in');
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user === null) {
            return;
        }

        activity('auth')
            ->causedBy($event->user)
            ->event('logout')
            ->withProperties($this->context($event->guard))
            ->log('logged_out');
    }

    public function handleFailed(Failed $event): void
    {
        $credentials = $event->credentials;
        unset($credentials['password']);

        activity('auth')
            ->causedBy($event->user)
            ->event('login_failed')
            ->withProperties($this->context($event->guard) + [
                'credentials' => $credentials,
            ])
            ->log('login_failed');
    }

    /**
     * @return array{guard: string|null, ip: string|null}
     */
    private function context(string $guard): array
    {
        return [
            'guard' => $guard,
            'ip' => Request::ip(),
        ];
    }
}
