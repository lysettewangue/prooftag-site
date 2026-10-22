<?php

declare(strict_types=1);

namespace App\Actions\Corporate;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\Partner;
use App\Models\User;
use App\Support\ContentCache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

final class UpdatePartner
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Partner $partner, User $actor, array $data): Partner
    {
        Gate::forUser($actor)->authorize('update', $partner);

        if (array_key_exists('name', $data)) {
            $name = trim((string) $data['name']);

            if ($name === '') {
                throw new DomainRuleViolation('Partner name is required.');
            }

            $data['name'] = $name;
        }

        if (array_key_exists('url', $data)) {
            $data['url'] = $this->normalizedUrl($data['url']);
        }

        $partner->fill(Arr::only($data, ['name', 'sort_order', 'url']));
        $partner->save();

        ContentCache::flush();

        return $partner->refresh();
    }

    private function normalizedUrl(mixed $value): ?string
    {
        $url = trim((string) $value);

        if ($url === '') {
            return null;
        }

        if (strlen($url) > 255 || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new DomainRuleViolation('Partner URL must be a valid address.');
        }

        return $url;
    }
}
