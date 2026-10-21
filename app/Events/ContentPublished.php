<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ContentPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Model $model,
        public User $actor,
    ) {}
}
