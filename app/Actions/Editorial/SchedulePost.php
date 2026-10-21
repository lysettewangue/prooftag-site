<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Actions\Publication\TransitionPublication;
use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\User;
use DateTimeInterface;

final class SchedulePost
{
    public function handle(Post $post, User $actor, DateTimeInterface $when): Post
    {
        /** @var Post $post */
        $post = (new TransitionPublication)->handle($post, PublicationStatus::Scheduled, $actor, $when);

        return $post;
    }
}
