<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\TeamMember;
use App\Models\TeamMemberTranslation;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class TranslateTeamMember
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(TeamMember $member, User $actor, array $data): TeamMemberTranslation
    {
        $locale = Locale::from((string) $data['locale']);

        Gate::forUser($actor)->authorize(
            $locale === Locale::French ? 'update' : 'translate',
            $member,
        );

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            throw new DomainRuleViolation('Team member title is required.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($title);
        }

        if ($slug === '') {
            $slug = Str::slug($member->full_name);
        }

        if ($slug === '') {
            throw new DomainRuleViolation('Team member slug is required.');
        }

        $duplicate = TeamMemberTranslation::query()
            ->where('locale', $locale->value)
            ->where('slug', $slug)
            ->where('team_member_id', '!=', $member->id)
            ->exists();

        if ($duplicate) {
            throw new DomainRuleViolation('Team member slugs must be unique per locale.');
        }

        $bio = $data['bio'] ?? null;
        $bio = is_string($bio) ? trim($bio) : null;

        $translation = $member->translations()->updateOrCreate(
            ['locale' => $locale->value],
            array_merge(
                Arr::only($data, ['meta_title', 'meta_description', 'canonical_path']),
                [
                    'slug' => $slug,
                    'title' => $title,
                    'bio' => $bio === '' ? null : $bio,
                ],
            ),
        );

        if (! $translation instanceof TeamMemberTranslation) {
            throw new DomainRuleViolation('Could not persist the team member translation.');
        }

        ContentCache::flush();

        return $translation;
    }
}
