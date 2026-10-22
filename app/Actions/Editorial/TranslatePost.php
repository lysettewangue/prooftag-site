<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\User;
use App\Support\BodyContent;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslatePost
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Post $post, User $actor, array $data): PostTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $post,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Post title is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($title);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Post slug is required.');
        }

        $duplicate = PostTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('post_id', '!=', $post->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Post slugs must be unique per locale.');
        }

        $summary = $data['summary'] ?? null;
        $summary = is_string($summary) ? trim($summary) : null;

        $translation = $post->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'summary' => $summary === '' ? null : $summary,
                    'body' => BodyContent::fromForm($data['body'] ?? null),
                ],
            ),
        );

        if (! $translation instanceof PostTranslation) {
            throw new DomainRuleViolation('Could not persist the post translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
