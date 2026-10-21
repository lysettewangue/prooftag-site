<?php

declare(strict_types=1);

namespace App\Actions\Seo;

use App\Models\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

final class UpsertRedirect
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data, ?Redirect $redirect = null): Redirect
    {
        $payload = [
            'from_host' => (string) $data['from_host'],
            'from_path' => (string) $data['from_path'],
            'to_path' => (string) $data['to_path'],
            'status_code' => (int) ($data['status_code'] ?? 301),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];

        if ($redirect === null) {
            Gate::forUser($actor)->authorize('create', Redirect::class);

            $redirect = Redirect::query()->create($payload);
        } else {
            Gate::forUser($actor)->authorize('update', $redirect);

            $redirect->fill($payload)->save();
        }

        return $redirect->refresh();
    }
}
