<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateTeamMember
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(TeamMember $member, User $actor, array $data): TeamMember
    {
        Gate::forUser($actor)->authorize('update', $member);

        if (array_key_exists('full_name', $data)) {
            $fullName = trim((string) $data['full_name']);

            if ($fullName === '') {
                throw new DomainRuleViolation('Team member name is required.');
            }

            $data['full_name'] = $fullName;
        }

        $member->fill(Arr::only($data, ['full_name', 'sort_order']));
        $member->save();

        ContentCache::flush();

        return $member->refresh();
    }
}
