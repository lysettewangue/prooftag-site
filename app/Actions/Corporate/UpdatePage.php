<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Models\Page;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class UpdatePage
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Page $page, User $actor, array $data): Page
    {
        Gate::forUser($actor)->authorize('update', $page);

        ContentCache::flush();

        return $page->refresh();
    }
}
