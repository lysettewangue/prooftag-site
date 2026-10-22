<?php

declare(strict_types=1);

namespace App\Actions\Seo;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\Redirect;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;

final class UpsertRedirect
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data, ?Redirect $redirect = null): Redirect
    {
        $payload = $this->normalized($data);

        try {
            if ($redirect === null) {
                Gate::forUser($actor)->authorize('create', Redirect::class);

                $this->assertUnique($payload['from_host'], $payload['from_path']);

                $redirect = Redirect::query()->create($payload);
            } else {
                Gate::forUser($actor)->authorize('update', $redirect);

                $this->assertUnique($payload['from_host'], $payload['from_path'], (int) $redirect->id);

                $redirect->fill($payload)->save();
            }
        } catch (QueryException $exception) {
            throw new DomainRuleViolation('Redirect host and path must be unique.');
        }

        return $redirect->refresh();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{from_host: string, from_path: string, to_path: string, status_code: int, is_active: bool}
     */
    private function normalized(array $data): array
    {
        $host = trim((string) $data['from_host']);
        $from = trim((string) $data['from_path']);
        $to = trim((string) $data['to_path']);
        $code = (int) ($data['status_code'] ?? 301);

        /** @var list<string> $hosts */
        $hosts = config('cms.allowed_redirect_hosts', []);

        if (! in_array($host, $hosts, true)) {
            throw new DomainRuleViolation('Redirect host is not in the approved host list.');
        }

        if ($from === '' || ! str_starts_with($from, '/')) {
            throw new DomainRuleViolation('Redirect from path must start with /.');
        }

        if ($to === '' || ! str_starts_with($to, '/')) {
            throw new DomainRuleViolation('Redirect to path must start with /.');
        }

        if (! in_array($code, [301, 302], true)) {
            throw new DomainRuleViolation('Redirect status must be 301 or 302.');
        }

        return [
            'from_host' => $host,
            'from_path' => $from,
            'to_path' => $to,
            'status_code' => $code,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];
    }

    private function assertUnique(string $host, string $path, ?int $ignoreId = null): void
    {
        $exists = Redirect::query()
            ->where('from_host', $host)
            ->where('from_path', $path)
            ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            throw new DomainRuleViolation('Redirect host and path must be unique.');
        }
    }
}
