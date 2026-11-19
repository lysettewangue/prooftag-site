<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Removes the unverified editorial samples that were previously displayed as
 * public news. The audited legacy blog contained no published articles.
 */
final class HomepageEditorialSeeder extends Seeder
{
    /** @var list<string> */
    private const UNVERIFIED_SLUGS = [
        'solutions-numeriques-routes-plus-sures-cameroun',
        'digital-solutions-safer-roads-cameroon',
        'controle-technique-levier-securite-durabilite-vehicules',
        'technical-inspection-safety-vehicle-durability',
        'prooftag-catis-fonctionnalites-analytiques',
        'prooftag-catis-analytics-features',
        'themes-controle-technique-securise',
        'secured-technical-inspection-themes',
    ];

    public function run(): void
    {
        $posts = Post::query()
            ->withTrashed()
            ->whereHas(
                'translations',
                fn ($query) => $query->whereIn('slug', self::UNVERIFIED_SLUGS),
            )
            ->get();

        foreach ($posts as $post) {
            $post->clearMediaCollection('images');
            $post->forceDelete();
        }

        ContentCache::flush();
    }
}
