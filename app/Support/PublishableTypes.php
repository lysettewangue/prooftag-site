<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Career;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Expertise;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use App\Models\SecurityPoint;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;

final class PublishableTypes
{
    /**
     * @return list<class-string<Model>>
     */
    public static function classes(): array
    {
        return [
            Product::class,
            Post::class,
            Category::class,
            Resource::class,
            Page::class,
            TeamMember::class,
            Partner::class,
            Equipment::class,
            Expertise::class,
            SecurityPoint::class,
            Career::class,
        ];
    }
}
