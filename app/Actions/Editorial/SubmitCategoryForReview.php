<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Actions\Publication\TransitionPublication;
use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\User;

final class SubmitCategoryForReview
{
    public function handle(Category $category, User $actor): Category
    {
        /** @var Category $category */
        $category = (new TransitionPublication)->handle($category, PublicationStatus::UnderReview, $actor);

        return $category;
    }
}
