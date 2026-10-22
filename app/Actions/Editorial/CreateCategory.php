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
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data = []): Category
    {
        Gate::forUser($actor)->authorize('create', Category::class);

        $category = Category::query()->create([
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $category;
    }
}
