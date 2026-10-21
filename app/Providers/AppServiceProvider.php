<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\CentreVerified;
use App\Events\ContactReceived;
use App\Events\ContentPublished;
use App\Listeners\LogContentLifecycle;
use App\Listeners\LogStaffAuthentication;
use App\Listeners\LogStaffAuthorization;
use App\Listeners\SendContactReceivedMail;
use App\Models\Role;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)->setRoleClass(Role::class);

        Event::listen(Login::class, [LogStaffAuthentication::class, 'handleLogin']);
        Event::listen(Logout::class, [LogStaffAuthentication::class, 'handleLogout']);
        Event::listen(Failed::class, [LogStaffAuthentication::class, 'handleFailed']);
        Event::listen(RoleAttachedEvent::class, [LogStaffAuthorization::class, 'roleAttached']);
        Event::listen(RoleDetachedEvent::class, [LogStaffAuthorization::class, 'roleDetached']);
        Event::listen(PermissionAttachedEvent::class, [LogStaffAuthorization::class, 'permissionAttached']);
        Event::listen(PermissionDetachedEvent::class, [LogStaffAuthorization::class, 'permissionDetached']);
        Event::listen(ContentPublished::class, [LogContentLifecycle::class, 'published']);
        Event::listen(CentreVerified::class, [LogContentLifecycle::class, 'centreVerified']);
        Event::listen(ContactReceived::class, SendContactReceivedMail::class);
    }
}
