<?php

declare(strict_types=1);

namespace App\Actions\Editorial;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslatePost
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Post $post, User $actor, array $data): PostTranslation
    {
        Gate::forUser($actor)->authorize('translate', $post);

        $locale = Locale::from((string) $data['locale']);

        $translation = $post->translations()->updateOrCreate(
            ['locale' => $locale->value],
            Arr::only($data, ['slug', 'title', 'summary', 'body', 'meta_title', 'meta_description', 'canonical_path']),
        );

        if (! $translation instanceof PostTranslation) {
            throw new DomainRuleViolation('Could not persist the post translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
