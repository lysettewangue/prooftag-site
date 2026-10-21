<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Actions\Publication\TransitionPublication;
use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\User;

final class ArchivePost
{
    public function handle(Post $post, User $actor): Post
    {
        /** @var Post $post */
        $post = (new TransitionPublication)->handle($post, PublicationStatus::Archived, $actor);

        return $post;
    }
}
