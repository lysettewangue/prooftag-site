<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\User;
use App\Support\ContentCache;
use App\Support\PageOverview;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class TranslatePage
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Page $page, User $actor, array $data): PageTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $page,
        );

        $slug = $page->code->slugs()[$locale->value];
        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Page title is required.');
        }

        if (isset($data['slug']) && $data['slug'] !== $slug) {
            throw new DomainRuleViolation('Page slugs are frozen to the sitemap.');
        }

        $blocks = $data['blocks'] ?? PageOverview::fromForm($data['overview'] ?? null);

        if (! is_array($blocks)) {
            throw new DomainRuleViolation('Page blocks must be an array.');
        }

        $translation = $page->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'blocks' => $blocks,
                ],
            ),
        );

        if (! $translation instanceof PageTranslation) {
            throw new DomainRuleViolation('Could not persist the page translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
