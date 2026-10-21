<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Models\Category;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class UpdateCategory
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Category $category, User $actor, array $data): Category
    {
        Gate::forUser($actor)->authorize('update', $category);

        if (array_key_exists('sort_order', $data)) {
            $category->sort_order = (int) $data['sort_order'];
            $category->save();
        }

        ContentCache::flush();

        return $category->refresh();
    }
}
