<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Facades\Gate;

final class CreateTeamMember
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): TeamMember
    {
        Gate::forUser($actor)->authorize('create', TeamMember::class);

        $fullName = trim((string) ($data['full_name'] ?? ''));

        if ($fullName === '') {
            throw new DomainRuleViolation('Team member name is required.');
        }

        $member = TeamMember::query()->create([
            'full_name' => $fullName,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'publication_status' => PublicationStatus::Draft,
        ]);

        ContentCache::flush();

        return $member;
    }
}
