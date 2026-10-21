<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Enums\PostKind;
use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreatePost
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): Post
    {
        Gate::forUser($actor)->authorize('create', Post::class);

        $post = Post::query()->create([
            'kind' => PostKind::from((string) $data['kind']),
            'author_id' => $actor->id,
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $post;
    }
}
