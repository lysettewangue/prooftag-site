<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final class RelatedContent
{
    /**
     * @return Collection<int, Post>
     */
    public function postsForProduct(Product $product, string $locale, int $limit = 5): Collection
    {
        return Post::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->whereHas('products', fn ($query) => $query->where('products.id', $product->id))
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
