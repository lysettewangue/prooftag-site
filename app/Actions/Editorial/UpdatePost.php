<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Enums\PostKind;
use App\Models\Post;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class UpdatePost
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Post $post, User $actor, array $data): Post
    {
        Gate::forUser($actor)->authorize('update', $post);

        if (isset($data['kind'])) {
            $post->kind = PostKind::from((string) $data['kind']);
        }

        if (array_key_exists('category_ids', $data) && is_array($data['category_ids'])) {
            $post->categories()->sync($data['category_ids']);
        }

        if (array_key_exists('product_ids', $data) && is_array($data['product_ids'])) {
            $post->products()->sync($data['product_ids']);
        }

        if (array_key_exists('expertise_ids', $data) && is_array($data['expertise_ids'])) {
            $post->expertises()->sync($data['expertise_ids']);
        }

        $post->save();
        ContentCache::flush();

        return $post->refresh();
    }
}
