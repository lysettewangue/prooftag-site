<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CareerKind;
use App\Models\Career;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdateCareer
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Career $career, User $actor, array $data): Career
    {
        Gate::forUser($actor)->authorize('update', $career);

        if (isset($data['kind'])) {
            $career->kind = CareerKind::from((string) $data['kind']);
        }

        if (array_key_exists('application_url', $data)) {
            $data['application_url'] = $this->normalizedUrl($data['application_url']);
        }

        if (array_key_exists('closes_at', $data) && ! filled($data['closes_at'])) {
            $data['closes_at'] = null;
        }

        $career->fill(Arr::only($data, ['closes_at', 'application_url']));
        $career->save();

        ContentCache::flush();

        return $career->refresh();
    }

    private function normalizedUrl(mixed $value): ?string
    {
        $url = trim((string) $value);

        if ($url === '') {
            return null;
        }

        if (strlen($url) > 255 || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new DomainRuleViolation('Career application URL must be a valid address.');
        }

        return $url;
    }
}
