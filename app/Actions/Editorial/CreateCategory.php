<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreateCategory
{
    public function handle(User $actor): Category
    {
        Gate::forUser($actor)->authorize('create', Category::class);

        $category = Category::query()->create([
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $category;
    }
}
