<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\InspectionCentre;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class SiteSearch
{
    /**
     * @return array{products: Collection<int, Product>, posts: Collection<int, Post>, centres: Collection<int, InspectionCentre>, resources: Collection<int, CmsResourceModel>}
     */
    public function search(string $locale, string $term): array
    {
        $term = trim($term);

        if ($term === '') {
            return [
                'products' => new Collection,
                'posts' => new Collection,
                'centres' => new Collection,
                'resources' => new Collection,
            ];
        }

        $like = '%'.addcslashes($term, '%_\\').'%';

        return [
            'products' => Product::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->where(function (Builder $query) use ($locale, $like): void {
                    $query
                        ->where('public_name', 'like', $like)
                        ->orWhereHas('translations', function (Builder $translations) use ($locale, $like): void {
                            $translations->where('locale', $locale)
                                ->where(function (Builder $inner) use ($like): void {
                                    $inner->where('summary', 'like', $like)->orWhere('slug', 'like', $like);
                                });
                        });
                })
                ->orderBy('sort_order')
                ->limit(10)
                ->get(),
            'posts' => Post::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->whereHas('translations', function (Builder $translations) use ($locale, $like): void {
                    $translations->where('locale', $locale)
                        ->where(function (Builder $inner) use ($like): void {
                            $inner->where('title', 'like', $like)
                                ->orWhere('summary', 'like', $like)
                                ->orWhere('slug', 'like', $like);
                        });
                })
                ->latest('published_at')
                ->limit(10)
                ->get(),
            'centres' => InspectionCentre::query()
                ->publiclyListed()
                ->forLocale($locale)
                ->where(function (Builder $query) use ($locale, $like): void {
                    $query
                        ->where('operator_name', 'like', $like)
                        ->orWhere('city', 'like', $like)
                        ->orWhereHas('translations', function (Builder $translations) use ($locale, $like): void {
                            $translations->where('locale', $locale)->where('name', 'like', $like);
                        });
                })
                ->orderBy('city')
                ->limit(10)
                ->get(),
            'resources' => Resource::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->whereHas('translations', function (Builder $translations) use ($locale, $like): void {
                    $translations->where('locale', $locale)
                        ->where(function (Builder $inner) use ($like): void {
                            $inner->where('title', 'like', $like)
                                ->orWhere('summary', 'like', $like)
                                ->orWhere('slug', 'like', $like);
                        });
                })
                ->latest('published_at')
                ->limit(10)
                ->get(),
        ];
    }
}
