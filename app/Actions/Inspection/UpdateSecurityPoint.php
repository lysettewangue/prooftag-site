<?php

declare(strict_types=1);

namespace App\Actions\Inspection;

use App\Models\SecurityPoint;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class UpdateSecurityPoint
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(SecurityPoint $point, User $actor, array $data): SecurityPoint
    {
        Gate::forUser($actor)->authorize('update', $point);

        ContentCache::flush();

        return $point->refresh();
    }
}
