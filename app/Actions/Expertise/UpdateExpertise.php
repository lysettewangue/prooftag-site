<?php

declare(strict_types=1);

namespace App\Actions\Expertise;

use App\Models\Expertise;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateExpertise
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Expertise $expertise, User $actor, array $data): Expertise
    {
        Gate::forUser($actor)->authorize('update', $expertise);

        $expertise->fill(Arr::only($data, ['icon']));
        $expertise->save();

        ContentCache::flush();

        return $expertise->refresh();
    }
}
